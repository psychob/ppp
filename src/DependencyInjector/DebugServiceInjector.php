<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\DependencyInjector;

    use PsychoB\WebFramework\Config\OptionManager;
    use PsychoB\WebFramework\Container\ServiceContainer;
    use PsychoB\WebFramework\Debug\TimeBus;
    use PsychoB\WebFramework\Debug\Timer;
    use PsychoB\WebFramework\DynamicObject\DynamicObjectBuilder;
    use PsychoB\WebFramework\Utility\Arr;

    class DebugServiceInjector implements InjectorInterface
    {
        protected ServiceInjector $injector;
        protected array $trackTime = [];

        public function __construct(ServiceInjector $injector)
        {
            $this->injector = $injector;
            $this->injector->getContainer()
                           ->set(Injector::class,
                               new Injector($this->injector->getContainer(), $this));
            $this->trackTime = $injector->make(OptionManager::class)
                                        ->get('debug.services.tracktime', []);
        }

        public function inject($callable, array $arguments = [])
        {
            return $this->injector->inject($callable, $arguments);
        }

        public function make(string $class, array $arguments = []): object
        {
            if (Arr::inArray($this->trackTime, $class)) {
                $object = $this->injector->make($class, $arguments);
                $obj = DynamicObjectBuilder::new()
                    ->extends($class)
                    ->createProperty('timer', new Timer($this->injector->make(TimeBus::class), $class))
                    ->createProperty('object', $object)
                    ->passThroughFunction(function ($that, $methodName, $arguments) {
                        return $that->timer->timeIt(function () use ($that, $methodName, $arguments) {
                            return $that->object->$methodName(...$arguments);
                        }, $methodName);
                    })->make();

                $this->injector->make(ServiceContainer::class)->set($class, $obj);

                Arr::removeElementsByValue($this->trackTime, [$class]);

                return $obj;
            } else {
                return $this->injector->make($class, $arguments);
            }
        }
    }
