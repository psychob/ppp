<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Debug;

    class ClockSection
    {
        protected Clock $clock;
        protected string $name;
        protected int $startTime;

        /**
         * ClockSection constructor.
         *
         * @param Clock  $clock
         * @param string $name
         */
        public function __construct(Clock $clock, string $name)
        {
            $this->clock = $clock;
            $this->name = $name;

            $this->startTime = $this->clock->trigger(sprintf('%s: START', $this->name));
        }

        public function __destruct()
        {
            $this->clock->trigger(sprintf('%s: END', $this->name), $this->startTime);
        }
    }
