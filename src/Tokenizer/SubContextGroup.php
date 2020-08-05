<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer;

    class SubContextGroup
    {
        private string $name;
        private TokenizerInterface $tokenizer;

        /** @var string[] */
        private array $start;
        /** @var string[] */
        private array $end;

        /**
         * SubContextParser constructor.
         *
         * @param string    $name
         * @param string[]  $start
         * @param string[]  $end
         * @param TokenizerInterface $tokenizer
         */
        public function __construct(
            string $name,
            array $start,
            array $end,
            TokenizerInterface $tokenizer
        ) {
            $this->name = $name;
            $this->start = $start;
            $this->end = $end;
            $this->tokenizer = $tokenizer;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function getTokenizer(): TokenizerInterface
        {
            return $this->tokenizer;
        }

        /**
         * @return string[]
         */
        public function getStart(): array
        {
            return $this->start;
        }

        /**
         * @return string[]
         */
        public function getEnd(): array
        {
            return $this->end;
        }
    }
