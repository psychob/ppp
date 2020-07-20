<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Debug;

    class TimeEvent
    {
        public const SYSTEM_START = 1;
        public const SYSTEM_END   = 2;
        public const SYSTEM_OTHER = 3;

        private int $timeAbsolute;
        private int $memoryCurrent;
        private int $memoryPeak;
        private string $tagClass;
        private ?string $tagTag;
        private int $tagSystem;
        private ?TimeEvent $previous;

        /**
         * TimeEvent constructor.
         *
         * @param int            $timeAbsolute
         * @param int            $memoryCurrent
         * @param int            $memoryPeak
         * @param string         $tagClass
         * @param string|null    $tagTag
         * @param int            $tagSystem
         * @param TimeEvent|null $previous
         */
        public function __construct(
            int $timeAbsolute,
            int $memoryCurrent,
            int $memoryPeak,
            string $tagClass,
            ?string $tagTag,
            int $tagSystem,
            ?TimeEvent $previous = null
        ) {
            $this->timeAbsolute = $timeAbsolute;
            $this->memoryCurrent = $memoryCurrent;
            $this->memoryPeak = $memoryPeak;
            $this->tagClass = $tagClass;
            $this->tagTag = $tagTag;
            $this->tagSystem = $tagSystem;
            $this->previous = $previous;
        }
    }
