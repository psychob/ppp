<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer;

    use PsychoB\WebFramework\Tokenizer\Tokens\TokenInterface;

    interface TokenizerInterface
    {
        /**
         * @param string $str
         *
         * @return TokenInterface[]
         */
        public function tokenize(string $str): iterable;

        public function isSingleConsuming(): bool;
    }
