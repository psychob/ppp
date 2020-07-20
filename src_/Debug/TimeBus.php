<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Debug;

    use PsychoB\WebFramework\Utils\Time;

    class TimeBus
    {
        protected array $events = [];
        protected float $startTime = 0.0;
        protected ?float $lastEventTime = null;

        /**
         * TimeBus constructor.
         */
        public function __construct()
        {
            $this->startTime = hrtime(true);
        }

        public function trigger(string $eventName, ?string $class = null, float $diff = null)
        {
            $currentMemoryUsage = memory_get_usage(false);
            $totalAllocatedFromMemory = memory_get_usage(true);
            $lastEventDiff = $this->lastEventTime;
            $currentTime = $this->lastEventTime = $this->currentTime();
            if ($lastEventDiff) {
                $lastEventDiff = $currentTime - $lastEventDiff;
            }

            $this->events[] = [
                'time_absolute' => $currentTime,
                'time_absolute_p' => Time::prettyPrecise($currentTime, 'ns'),
                'time_diff' => $lastEventDiff,
                'time_diff_p' => Time::prettyPrecise($lastEventDiff ?? 0, 'ns'),
                'metadata_class' => $class,
                'metadata_name' => $eventName,
                'metadata_diff' => $diff,
                'metadata_diff_p' => Time::prettyPrecise($diff ?? 0, 'ns'),
                'memory_current' => $currentMemoryUsage,
                'memory_system' => $totalAllocatedFromMemory,
                'memory_used' => number_format($currentMemoryUsage / $totalAllocatedFromMemory, 3),
            ];
        }

        private function currentTime(): float
        {
            return hrtime(true) - $this->startTime;
        }
    }
