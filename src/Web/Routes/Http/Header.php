<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http;

    class Header
    {
        protected string $name;
        protected string $value;

        /**
         * Header constructor.
         *
         * @param string $name
         * @param string $value
         */
        public function __construct(string $name, string $value)
        {
            $this->name = $name;
            $this->value = $value;
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

        /**
         * @return string
         */
        public function getValue(): string
        {
            return $this->value;
        }

        public static function fromString($key, $value): Header
        {
            return new Header($key, $value);
        }
    }
