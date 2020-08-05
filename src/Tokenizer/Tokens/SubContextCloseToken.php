<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer\Tokens;

    class SubContextCloseToken extends AbstractToken implements TokenInterface
    {
        private string $tokenizerName;

        public function __construct(string $token, int $start, string $tokenizerName)
        {
            $this->tokenizerName = $tokenizerName;

            parent::__construct($token, $start);
        }

        /**
         * @return string
         */
        public function getTokenizerName(): string
        {
            return $this->tokenizerName;
        }
    }
