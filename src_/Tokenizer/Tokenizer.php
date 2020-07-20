<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Tokenizer;

    class Tokenizer
    {
        protected string $str;
        protected string $delimiters = " \t\r\n";
        protected int $strLength;

        /**
         * Tokenizer constructor.
         *
         * @param string $str
         * @param string $delimiters
         */
        public function __construct(string $str, string $delimiters = null)
        {
            $this->str = $str;
            $this->strLength = strlen($str);

            if ($delimiters !== null) {
                $this->delimiters = $delimiters;
            }
        }

        // our state machine
        protected int $position = 0;
        protected int $lastReconsideredPosition = -1;
        protected int $positionLength = 0;

        public function peekWord(): string
        {
            if ($this->position !== $this->lastReconsideredPosition) {
                $this->ignoreWord();
            }

            return $this->currentSlice();
        }

        public function fetchWord(): string
        {
            try {
                return $this->peekWord();
            } finally {
                $this->ignoreWord();
            }
        }

        protected function currentSlice(): string
        {
            if ($this->position >= $this->strLength) {
                return "\0";
            } else {
                return substr($this->str, $this->position, $this->positionLength);
            }
        }

        protected function ignoreDelimiters(): void
        {
            while ($this->position + $this->positionLength < $this->strLength &&
                strpos($this->delimiters, $this->str[$this->position]) !== false) {
                $this->position++;
            }
        }

        private function swallowNonDelimiters(): void
        {
            while ($this->position + $this->positionLength < $this->strLength &&
                   strpos($this->delimiters, $this->str[$this->position + $this->positionLength]) === false) {
                $this->positionLength++;
            }
        }

        public function ignoreWord(): void
        {
            if ($this->lastReconsideredPosition !== -1) {
                $this->position = $this->position + $this->positionLength;
            }
            $this->positionLength = 0;
            $this->ignoreDelimiters();
            $this->swallowNonDelimiters();
            $this->lastReconsideredPosition = $this->position;
        }
    }
