<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Http\Headers;

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

        public function getName(): string
        {
            return $this->name;
        }

        public function getValue(): string
        {
            return $this->value;
        }

        public static function fromString(string $key, string $value): self
        {
            return new Header($key, $value);
        }
    }
