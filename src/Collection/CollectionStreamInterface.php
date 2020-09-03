<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection;

    use Countable;
    use Iterator;
    use PsychoB\WebFramework\Collection\Enum\SortDirectionEnum;

    interface CollectionStreamInterface extends Iterator, Countable
    {
        public function filterKey($callable): CollectionStreamInterface;

        public function filterValue($callable): CollectionStreamInterface;

        public function filterOutEmpty(): CollectionStreamInterface;

        public function filter($callable): CollectionStreamInterface;

        public function mapKey($callable): CollectionStreamInterface;

        public function mapValue($callable): CollectionStreamInterface;

        public function map($callable): CollectionStreamInterface;

        public function sort(
            int $direction = SortDirectionEnum::ASCENDING,
            bool $preserveKeys = true
        ): CollectionStreamInterface;

        public function sortKeys(
            int $direction = SortDirectionEnum::ASCENDING
        ): CollectionStreamInterface;

        public function sortBy($callable, bool $preserveKeys = true): CollectionStreamInterface;

        public function sortByField(
            string $field,
            int $direction = SortDirectionEnum::ASCENDING,
            bool $preserveKeys = true
        ): CollectionStreamInterface;

        public function toArray(): array;
    }
