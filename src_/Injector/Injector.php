<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Injector;

    use PsychoB\WebFramework\Container\ContainerInterface;
    use PsychoB\WebFramework\DependencyInjector\DynamicInjector;
    use PsychoB\WebFramework\Environment\ApplicationHints;
    use PsychoB\WebFramework\Environment\ApplicationInterface;
    use ReflectionClass;
    use ReflectionFunctionAbstract;
    use ReflectionMethod;

    class Injector implements InjectorInterface
    {
        protected ContainerInterface $container;
        protected array $constructedStack = [];

        /**
         * Injector constructor.
         *
         * @param ContainerInterface $container
         */
        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        /** @inheritDoc */
        public function make(string $class): object
        {
            return $this->inject([$class, '__construct']);
        }

        /** @inheritDoc */
        public function inject($callable, array $arguments = [])
        {
            if (is_array($callable)) {
                if (count($callable) === 2) {
                    if (is_string($callable[1])) {
                        if (is_string($callable[0])) {
                            return $this->injectIntoString($callable[0], $callable[1], $arguments);
                        } elseif (is_object($callable[0])) {
                            return $this->injectIntoObject($callable[0], $callable[1], $arguments);
                        }
                    }
                }

                throw new InjectInvalidFormatException($callable);
            }
        }

        private function injectIntoString(string $class, string $method, array $arguments)
        {
            if ($method === '__construct') {
                return $this->createNewInstance($class, $arguments);
            }

            $klass = new ReflectionClass($class);
            $rethod = $klass->getMethod($method);

            if (!$rethod->isStatic()) {
                throw new InvokingNonStaticMethodThroughStaticContextException($class, $method);
            }

            $parameters = $this->fetchParametersFromFunction($rethod, $arguments, $class, $method);

            return $rethod->invokeArgs(null, $parameters);
        }

        private function createNewInstance(string $class, array $arguments): object
        {
            try {
                array_push($this->constructedStack, $class);

                $klass = new ReflectionClass($class);
                $konstructor = $klass->getConstructor();

                if ($konstructor === null) {
                    return new $class();
                }

                $parameters = $this->fetchParametersFromFunction($konstructor, $arguments, $class, '__construct');

                return new $class(...$parameters);
            } finally {
                array_pop($this->constructedStack);
            }
        }

        private function fetchParametersFromFunction(
            ReflectionFunctionAbstract $function,
            array $arguments,
            string $dbgClass,
            string $dbgMethod
        ): array {
            $ret = [];

            if ($this->container->has(DynamicInjector::class)) {
                /** @var DynamicInjector $fetcherObject */
                $fetcherObject = $this->container->get(DynamicInjector::class);
                $fetcher = function ($class) use ($fetcherObject) {
                    return $fetcherObject->fetch($class);
                };
            } else {
                $fetcher = function ($class) {
                    return $this->make($class);
                };
            }

            foreach ($function->getParameters() as $param) {
                $providedObject = null;
                $provided = false;

                if (array_key_exists($param->getPosition(), $arguments)) {
                    $providedObject = $arguments[$param->getPosition()];
                    $provided = true;
                } else if (array_key_exists($param->getName(), $arguments)) {
                    $providedObject = $arguments[$param->getName()];
                    $provided = true;
                }

                if ($provided) {
                    if ($providedObject instanceof InjectPlaceholder) {
                        if (!($providedObject instanceof DefaultInjectPlaceholder)) {
                            $ret[] = $providedObject;
                            continue;
                        }
                    }
                }

                $type = $param->getType();
                if ($type !== null) {
                    if (!$type->isBuiltin()) {
                        $ret[] = $fetcher($type->getName());
                        continue;
                    }

                    if ($type->allowsNull()) {
                        $ret[] = null;
                        continue;
                    }
                }

                // our last resort weapon is checking for hints in class
                if ($function instanceof ReflectionMethod) {
                    $klass = $function->getDeclaringClass();

                    if ($klass->implementsInterface(ConstructorHint::class)) {
                        $hints = call_user_func([$klass->getName(), '_GetConstructorHint']);
                        $hint = null;

                        if (array_key_exists($param->getPosition(), $hints)) {
                            $hint = $hints[$param->getPosition()];
                        } else if (array_key_exists($param->getName(), $hints)) {
                            $hint = $hints[$param->getName()];
                        }

                        if ($hint !== null) {
                            switch ($hint) {
                                case ApplicationHints::APP_DIRECTORY:
                                    $ret[] = $fetcher(ApplicationInterface::class)->getAppDirectory();
                                    continue 2;

                                case ApplicationHints::CALLER_CLASS:
                                    $ret[] = $this->constructedStack[count($this->constructedStack) - 2];
                                    continue 2;
                            }
                        }
                    }
                }

                throw new CantInjectArgumentException($dbgClass, $dbgMethod, $param);
            }

            return $ret;
        }
    }
