<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection;

    interface CollectionStreamInterface extends \Iterator, \Countable
    {
        public function filterKey($callable): CollectionStreamInterface;

        public function filterValue($callable): CollectionStreamInterface;

        public function filterOutEmpty(): CollectionStreamInterface;

        public function mapKey($callable): CollectionStreamInterface;

        public function mapValue($callable): CollectionStreamInterface;

        public function toArray(): array;
    }
