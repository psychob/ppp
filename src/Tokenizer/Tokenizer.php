<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer;

    use PsychoB\WebFramework\Tokenizer\Tokens\LiteralToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\WhitespaceToken;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Str;

    class Tokenizer
    {
        public static function create(): Tokenizer
        {
            return new Tokenizer();
        }

        /** @var ElementGroup[] */
        private array $elements = [];

        /** @var SubContextGroup[] */
        private array $subContextParser = [];

        private bool $parseOnlyInside = false;
        private string $outsideClass = '';

        /**
         * @param string          $name
         * @param string[]|string $characters
         * @param bool            $merge
         * @param string          $tokenClass
         *
         * @return $this
         */
        public function addElementGroup(string $name, $characters, bool $merge, string $tokenClass): self
        {
            if (is_string($characters)) {
                $characters = Str::split($characters);
            }

            $this->elements[] = new ElementGroup($name, $characters, $merge, $tokenClass);

            return $this;
        }

        public function addWhitespaceGroup(): self
        {
            return $this->addElementGroup('whitespace', " \t\r\n", true, WhitespaceToken::class);
        }

        public function addLiteralGroup(): self
        {
            return $this->addElementGroup('literal', [], true, LiteralToken::class);
        }

        public function addSubContextParser(string $name, $start, $end, TokenizerInterface $tokenizer): self
        {
            if (is_string($start)) {
                $start = [$start];
            }

            if (is_string($end)) {
                $end = [$end];
            }

            $this->subContextParser[] = new SubContextGroup($name, $start, $end, $tokenizer);

            return $this;
        }

        public function parseOutside(bool $value, string $outsideClass = ''): self
        {
            $this->parseOnlyInside = !$value;
            $this->outsideClass = $outsideClass;

            return $this;
        }

        public function make(): TokenizerInterface
        {
            if (Arr::len($this->subContextParser) > 0) {
                if ($this->parseOnlyInside) {
                    return new SubContextInsideTokenizer($this->subContextParser, $this->outsideClass);
                } else {
                    return new SubContextOutsideTokenizer($this->elements, $this->subContextParser, $this->outsideClass);
                }
            }

            return new FlatTokenizer($this->elements);
        }
    }
