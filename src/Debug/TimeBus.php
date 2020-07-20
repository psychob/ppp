<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Debug;

    class TimeBus
    {
        protected array $events = [];
        protected int $hrStartTime;
        protected int $mtStartTime;

        public function __construct()
        {
            $this->hrStartTime = hrtime(true);
            $this->mtStartTime = microtime(true);

            $this->event(self::class, '__construct');
        }

        public function __destruct()
        {
            $this->event(self::class, '__destruct');
        }

        public function event(
            string $class,
            ?string $tag,
            int $system = TimeEvent::SYSTEM_OTHER,
            ?TimeEvent $previous = null
        ): TimeEvent {
            $now = hrtime(true);
            $memoryCurrent = memory_get_usage(false);
            $memoryPeak = memory_get_peak_usage(false);

            $event = new TimeEvent($now, $memoryCurrent, $memoryPeak, $class, $tag, $system, $previous);
            $this->events[] = $event;

            return $event;
        }
    }
