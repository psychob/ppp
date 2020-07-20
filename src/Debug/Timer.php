<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Debug;

    use PsychoB\WebFramework\DependencyInjector\Hints\ConstructorHint;
    use PsychoB\WebFramework\DependencyInjector\Hints\DoNotCacheInServiceContainerHint;
    use PsychoB\WebFramework\DependencyInjector\Hints\InjectorHint;

    class Timer implements DoNotCacheInServiceContainerHint, ConstructorHint
    {
        protected TimeBus $bus;
        protected string $callerClass;

        public function __construct(TimeBus $bus, string $callerClass)
        {
            $this->bus = $bus;
            $this->callerClass = $callerClass;
        }

        public function timeIt(callable $c, ?string $tag = null)
        {
            try {
                $event = $this->bus->event($this->callerClass, $tag, TimeEvent::SYSTEM_START);

                return $c();
            } finally {
                $this->bus->event($this->callerClass, $tag, TimeEvent::SYSTEM_END, $event);
            }
        }

        public static function ppp_internal__GetConstructorHint(): array
        {
            return [
                'callerClass' => InjectorHint::callerClass(),
            ];
        }
    }
