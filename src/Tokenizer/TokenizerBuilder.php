<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer;

    use PsychoB\WebFramework\Tokenizer\Tokens\LiteralToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\WhitespaceToken;
    use PsychoB\WebFramework\Utility\Str;

    class TokenizerBuilder
    {
        /** @var ElementGroup[] */
        private array $elements = [];

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

        public function make(): Tokenizer
        {
            return new Tokenizer($this->elements);
        }
    }
