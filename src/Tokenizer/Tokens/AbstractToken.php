<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer\Tokens;

    use PsychoB\WebFramework\Utility\Str;

    abstract class AbstractToken implements TokenInterface
    {
        private string $token;
        private int $start;
        private int $length;

        public function __construct(string $token, int $start)
        {
            $this->token = $token;
            $this->start = $start;
            $this->length = Str::len($token);
        }

        public function getToken(): string
        {
            return $this->token;
        }

        public function getStart(): int
        {
            return $this->start;
        }

        public function getLength(): int
        {
            return $this->length;
        }

        public function withAdjustedStart(int $offset): self
        {
            return new static($this->token, $this->start + $offset);
        }
    }
