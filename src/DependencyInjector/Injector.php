<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\DependencyInjector;

    use PsychoB\WebFramework\Bios\ApplicationInterface;
    use PsychoB\WebFramework\Container\ServiceContainerInterface;
    use PsychoB\WebFramework\DependencyInjector\Hints\ConstructorHint;
    use PsychoB\WebFramework\DependencyInjector\Hints\InjectorHint;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Web\Routes\RouteManager;
    use ReflectionFunctionAbstract;
    use ReflectionMethod;
    use ReflectionType;

    class Injector implements InjectorInterface
    {
        private ServiceContainerInterface $container;
        private InjectorInterface $dependencyInterface;
        private array $creatingClasses = [];

        public function __construct(ServiceContainerInterface $container, ?InjectorInterface $dependencyInterface = null)
        {
            $this->container = $container;
            $this->dependencyInterface = $dependencyInterface ?? $this;
        }

        public function inject($callable, array $arguments = [])
        {
            if (is_array($callable)) {
                if (count($callable) === 2) {
                    if (is_string($callable[1])) {
                        if (is_string($callable[0])) {
                            return $this->injectIntoString($callable[0], $callable[1], $arguments);
                        } else if (is_object($callable[0])) {
                            return $this->injectIntoObject($callable[0], $callable[1], $arguments);
                        }
                    }
                }
            }
        }

        public function make(string $class, array $arguments = []): object
        {
            return $this->inject([$class, '__construct'], $arguments);
        }

        private function injectIntoString(string $class, string $method, array $arguments)
        {
            if ($method === '__construct') {
                return $this->createNewInstance($class, $arguments);
            }
        }

        private function createNewInstance(string $class, array $arguments): object
        {
            try {
                $this->creatingClasses = Arr::push($this->creatingClasses, $class);
                $klass = new \ReflectionClass($class);
                $konstructor = $klass->getConstructor();

                if ($konstructor === null) {
                    // Reflection can return null on constructor, if in whole chain of extended object, no object defined
                    // it's own constructor

                    return new $class(...$arguments);
                }

                $args = $this->fetchArgumentsFromFunction($konstructor, $arguments, $class, '__construct');

                return new $class(...$args);
            } finally {
                Arr::popAndVerify($this->creatingClasses, $class);
            }
        }

        private function fetchArgumentsFromFunction(
            ReflectionFunctionAbstract $refMethod,
            array $arguments,
            string $dbgClass,
            string $dbgMethod
        ): array
        {
            $ret = [];
            $classSpecificHints = [];

            if ($refMethod instanceof ReflectionMethod) {
                if ($refMethod->getName() === '__construct') {
                    if ($refMethod->getDeclaringClass()->implementsInterface(ConstructorHint::class)) {
                        $classSpecificHints = call_user_func([$refMethod->class, 'ppp_internal__GetConstructorHint']);
                    }
                }
            }

            foreach ($refMethod->getParameters() as $param) {
                if (Arr::hasKey($arguments, $param->getName())) {
                    $ret[] = $arguments[$param->getName()];
                    continue;
                }

                if (Arr::hasKey($arguments, $param->getPosition())) {
                    $ret[] = $arguments[$param->getPosition()];
                    continue;
                }

                if (Arr::hasKey($classSpecificHints, $param->getName())) {
                    /** @var InjectorHint $hint */
                    $hint = $classSpecificHints[$param->getName()];

                    $ret[] = $this->fetchArgumentFromHint($hint);
                    continue;
                }

                if (Arr::hasKey($classSpecificHints, $param->getPosition())) {
                    /** @var InjectorHint $hint */
                    $hint = $classSpecificHints[$param->getPosition()];

                    $ret[] = $this->fetchArgumentFromHint($hint);
                    continue;
                }

                $type = $param->getType();
                if ($type) {
                    if (!$type->isBuiltin()) {
                        $ret[] = $this->dependencyInterface->make($type->getName(), []);
                        continue;
                    }

                    if ($type->allowsNull()) {
                        $ret[] = null;
                        continue;
                    }
                }

                $ret[] = null;
            }

            return $ret;
        }

        private function fetchArgumentFromHint(InjectorHint $hint)
        {
            switch ($hint->getTag()) {
                case InjectorHint::CALLER_NAME:
                    return Arr::fetchElementLast($this->creatingClasses, 1);

                case InjectorHint::APPLICATION_PATH:
                    return $this->dependencyInterface->make(ApplicationInterface::class)->getApplicationPath();

                case InjectorHint::FRAMEWORK_PATH:
                    return $this->dependencyInterface->make(ApplicationInterface::class)->getFrameworkPath();

                case InjectorHint::REQUESTED_ROUTE:
                    return $this->dependencyInterface->make(RouteManager::class)->getCurrentRoute();
            }
        }

        private function injectIntoObject(object $object, string $method, array $arguments)
        {
            $klass = new \ReflectionClass($object);
            $kethod = $klass->getMethod($method);

            $args = $this->fetchArgumentsFromFunction($kethod, $arguments, $klass->getName(), $method);
            return $kethod->invokeArgs($object, $args);
        }
    }
