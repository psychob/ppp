<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Collection;

    use PsychoB\WebFramework\Utils\Arr;

    class ArrayCollection implements CollectionInterface
    {
        private array $array = [];

        public function __construct(array $array)
        {
            $this->array = $array;
        }

        public function filter($fnc): CollectionInterface
        {
            // TODO: do it lazily
            $this->array = Arr::filter($this->array, $fnc);

            return $this;
        }

        public function map($fnc, int $options = Opt::MAP_DEFAULT): CollectionInterface
        {
            // TODO: do it lazy

            $ret = [];

            foreach ($this->array as $key => $value) {
                [$copyKey, $copyValue] = [$key, $value];

                $retVal = $fnc($value, $key);

                if (($options & Opt::MAP_REWRITE_KEYS)) {
                    $ret[$key] = $retVal;
                } else if (($options & Opt::MAP_RECOUNT_KEYS)) {
                    $ret[] = $retVal;
                } else {
                    $ret[$copyKey] = $retVal;
                }
            }

            $this->array = $ret;
            return $this;
        }

        public function toArray(): array
        {
            return $this->array;
        }
    }
