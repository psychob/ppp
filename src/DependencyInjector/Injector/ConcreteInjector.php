<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Injector;

    use PsychoB\WebFramework\Core\Kernel;
    use PsychoB\WebFramework\DependencyInjector\Container\ServiceContainer;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\ClassNotFoundException;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\InvalidCallableException;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\MethodIsNotPublicException;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\MethodIsNotStaticException;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\MethodNotFoundException;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\UnknownApplicationHintException;
    use PsychoB\WebFramework\DependencyInjector\Hints\ApplicationHints;
    use PsychoB\WebFramework\DependencyInjector\Hints\DependencyInjectorHints;
    use PsychoB\WebFramework\DependencyInjector\Hints\HintInterface;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Fnc;
    use PsychoB\WebFramework\Utility\Str;
    use ReflectionClass;
    use ReflectionException;
    use ReflectionFunction;
    use ReflectionFunctionAbstract;
    use ReflectionMethod;
    use ReflectionType;

    class ConcreteInjector implements InjectorInterface
    {
        private ServiceContainer $container;
        private array $inConstruction = [];

        public function __construct(ServiceContainer $container)
        {
            $this->container = $container;
        }

        public function inject($callable, array $arguments = [])
        {
            if (is_array($callable)) {
                if (Arr::len($callable) === 2) {
                    if (is_object($callable[0]) && is_string($callable[1])) {
                        return $this->injectIntoObject($callable[0], $callable[1], $arguments);
                    }

                    if (is_string($callable[0]) && is_string($callable[1])) {
                        return $this->injectIntoString($callable[0], $callable[1], $arguments);
                    }
                }

                throw new InvalidCallableException($callable, 'Invalid format when passing callable to inject');
            }

            if (is_string($callable)) {
                $match = Str::regExpMatch('/^([a-z0-9A-Z_\\\\]+)::([a-z0-9A-Z_\\\\]+)$/', $callable);

                if ($match) {
                    return $this->injectIntoString($match[1], $match[2], $arguments);
                }
            }

            if (is_callable($callable)) {
                return $this->injectIntoCallable($callable, $arguments);
            }

            throw new InvalidCallableException($callable, 'Unknown format for callable');
        }

        public function make(string $class, array $arguments = []): object
        {
            try {
                $this->inConstruction[] = $class;

                return $this->makeNewInstance($class, $arguments);
            } finally {
                array_pop($this->inConstruction);
            }
        }

        private function indirectMake(string $class, array $arguments = []): object
        {
            return $this->container->get(InjectorInterface::class)->make($class, $arguments);
        }

        private function makeNewInstance(string $class, array $arguments): object
        {
            $klass = Fnc::rethrow(
                fn () => new ReflectionClass($class),
                fn (ReflectionException $e) => new ClassNotFoundException($class, 'Failed when loading class', $e)
            );
            $konstructor = $klass->getConstructor();

            if ($konstructor === null) {
                return $this->makeNewObject($class, []);
            }

            $args = $this->prepareArguments($konstructor, $arguments, $this->getHintsFrom($klass, '__construct'));

            return $this->makeNewObject($class, $args);
        }

        private function makeNewObject(string $class, array $arguments): object
        {
            return new $class(...$arguments);
        }

        private function prepareArguments(ReflectionFunctionAbstract $method, array $arguments, array $hints): array
        {
            $ret = [];

            foreach ($method->getParameters() as $parameter) {
                if (Arr::hasKey($arguments, $parameter->getName())) {
                    $ret[] = $arguments[$parameter->getName()];
                    continue;
                }

                if (Arr::hasKey($arguments, $parameter->getPosition())) {
                    $ret[] = $arguments[$parameter->getPosition()];
                    continue;
                }

                if (Arr::hasKey($hints, $parameter->getName())) {
                    $ret[] = $this->resolveHint($hints[$parameter->getName()]);
                    continue;
                }

                if (Arr::hasKey($hints, $parameter->getPosition())) {
                    $ret[] = $this->resolveHint($hints[$parameter->getPosition()]);
                    continue;
                }

                if ($parameter->hasType()) {
                    /** @var ReflectionType $parameterType */
                    $parameterType = $parameter->getType();

                    if (!$parameterType->isBuiltin()) {
                        $ret[] = $this->indirectMake($parameterType->getName(), []);
                        continue;
                    }

                    if ($parameterType->allowsNull()) {
                        $ret[] = null;
                        continue;
                    }
                }

                if ($parameter->isDefaultValueAvailable()) {
                    $ret[] = $parameter->getDefaultValue();
                    continue;
                }
            }

            return $ret;
        }

        private function getHintsFrom(ReflectionClass $klass, string $method): array
        {
            if ($klass->implementsInterface(DependencyInjectorHints::class)) {
                $ret = call_user_func([$klass->getName(), 'ppp_internal__GetHints']);

                return $ret[$method] ?? [];
            }

            return [];
        }

        private function resolveHint(HintInterface $hint)
        {
            switch ($hint->getHint()) {
                case ApplicationHints::APPLICATION_PATH:
                    return $this->indirectMake(Kernel::class)->getApplicationPath();

                case ApplicationHints::FRAMEWORK_PATH:
                    return $this->indirectMake(Kernel::class)->getFrameworkPath();
            }

            throw new UnknownApplicationHintException($hint->getHint());
        }

        private function injectIntoObject(object $obj, string $method, array $arguments = [])
        {
            /** @noinspection PhpUnhandledExceptionInspection */
            $klass = new ReflectionClass($obj);
            $kethod = Fnc::rethrow(
                fn () => $klass->getMethod($method),
                fn (ReflectionException $e) => new MethodNotFoundException($method, $klass->getName(), 'Failed when looking for method', $e)
            );

            if (!$kethod->isPublic()) {
                throw new MethodIsNotPublicException($method, $klass->getName());
            }

            $args = $this->prepareArguments($kethod, $arguments, $this->getHintsFrom($klass, $method));

            return $kethod->invokeArgs($obj, $args);
        }

        private function injectIntoString(string $class, string $method, array $arguments = [])
        {
            if ($method === '__construct') {
                return $this->makeNewInstance($class, $arguments);
            }

            $klass = Fnc::rethrow(
                fn () => new ReflectionClass($class),
                fn (ReflectionException $e) => new ClassNotFoundException($class, 'Failed when loading class', $e)
            );
            /** @var ReflectionMethod $kethod */
            $kethod = Fnc::rethrow(
                fn () => $klass->getMethod($method),
                fn (ReflectionException $e) => new MethodNotFoundException($method, $klass->getName(), 'Failed when looking for method', $e)
            );

            if (!$kethod->isStatic()) {
                throw new MethodIsNotStaticException($method, $klass->getName());
            }

            if (!$kethod->isPublic()) {
                throw new MethodIsNotPublicException($method, $klass->getName());
            }

            $args = $this->prepareArguments($kethod, $arguments, $this->getHintsFrom($klass, $method));
            return call_user_func_array([$class, $method], $args);
        }

        private function injectIntoCallable(callable $callable, array $arguments = [])
        {
            $func = new ReflectionFunction($callable);

            $args = $this->prepareArguments($func, $arguments, []);

            return $func->invokeArgs($args);
        }
    }
