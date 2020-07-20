<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Http\Headers;

    use PsychoB\WebFramework\Collection\Opt;

    class Container
    {
        private array $headers;

        /**
         * HeadersContainer constructor.
         *
         * @param array $headers
         */
        public function __construct(array $headers)
        {
            $this->headers = $headers;
        }

        public static function fromArray(array $headers): self
        {
            return new Container(
                collect($headers)
                    ->map(fn($val, $key) => Header::fromString($key, $val), Opt::MAP_RECOUNT_KEYS)
                    ->toArray()
            );
        }
    }
