<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Debug;

    use PsychoB\WebFramework\DependencyInjector\DoNotCacheServiceHint;
    use PsychoB\WebFramework\Environment\ApplicationHints;
    use PsychoB\WebFramework\Injector\ConstructorHint;

    class Clock implements ConstructorHint, DoNotCacheServiceHint
    {
        protected TimeBus $timetable;
        protected string $name;
        protected float $startTime;

        /**
         * Clock constructor.
         *
         * @param TimeBus $timetable
         * @param string  $name
         */
        public function __construct(TimeBus $timetable, string $name)
        {
            $this->timetable = $timetable;
            $this->name = $name;
            $this->startTime = hrtime(true);
        }

        public static function _GetConstructorHint(): array
        {
            return [
                'name' => ApplicationHints::CALLER_CLASS,
            ];
        }

        public function trigger(string $eventName, int $lastTime = null)
        {
            $hrtime = hrtime(true);
            $this->timetable->trigger($eventName, $this->name, $lastTime ? $hrtime - $lastTime : $hrtime - $this->startTime);
            return $hrtime;
        }

        public function section(string $name): ClockSection
        {
            return new ClockSection($this, $name);
        }
    }
