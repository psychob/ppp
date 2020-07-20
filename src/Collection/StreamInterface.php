<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Collection;

    interface StreamInterface
    {
        public function filter(callable $filter): self;

        public function map(callable $mapper, int $opts = 0): self;

        public function sort(callable $sorter): self;

        public function recountKeys(): self;

        public function toArray(): array;
    }
