<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http;

    class Response
    {
        protected int $code = 200;

        public function getCode(): int
        {
            return $this->code;
        }

        public function hasCodeRange(int $base): bool
        {
            $code = $this->getCode();
            $base = $base - ($base % 100);

            return $code >= $base && $code <= $base + 99;
        }
    }
