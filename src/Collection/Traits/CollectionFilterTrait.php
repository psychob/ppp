<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Traits;

    use PsychoB\WebFramework\Collection\CollectionStreamInterface;
    use PsychoB\WebFramework\Collection\Enum\SortDirectionEnum;
    use PsychoB\WebFramework\Collection\Iterator\FilterIterator;
    use PsychoB\WebFramework\Collection\Iterator\FilterKeyIterator;
    use PsychoB\WebFramework\Collection\Iterator\FilterValueIterator;
    use PsychoB\WebFramework\Collection\Iterator\MapIterator;
    use PsychoB\WebFramework\Collection\Iterator\MapKeyIterator;
    use PsychoB\WebFramework\Collection\Iterator\MapValueIterator;
    use PsychoB\WebFramework\Collection\Iterator\SortByCallableIterator;
    use PsychoB\WebFramework\Collection\Iterator\SortByFieldIterator;
    use PsychoB\WebFramework\Collection\Iterator\SortIterator;
    use PsychoB\WebFramework\Collection\Iterator\SortKeysIterator;

    /**
     * Trait CollectionFilterTrait
     *
     * @author Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     * @since  0.1
     *
     * @mixin CollectionStreamInterface
     */
    trait CollectionFilterTrait
    {
        private array $filters = [];

        private function appendFilterClass(string $class, ...$arguments): CollectionStreamInterface
        {
            $this->filters[] = [ $class, $arguments ];

            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $this;
        }

        public function filterKey($callable): CollectionStreamInterface
        {
            return $this->appendFilterClass(FilterKeyIterator::class, $callable);
        }

        public function filterValue($callable): CollectionStreamInterface
        {
            return $this->appendFilterClass(FilterValueIterator::class, $callable);
        }

        public function filterOutEmpty(): CollectionStreamInterface
        {
            return $this->appendFilterClass(FilterValueIterator::class, fn ($value) => !empty($value));
        }

        public function mapKey($callable): CollectionStreamInterface
        {
            return $this->appendFilterClass(MapKeyIterator::class, $callable);
        }

        public function mapValue($callable): CollectionStreamInterface
        {
            return $this->appendFilterClass(MapValueIterator::class, $callable);
        }

        public function filter($callable): CollectionStreamInterface
        {
            return $this->appendFilterClass(FilterIterator::class, $callable);
        }

        public function map($callable): CollectionStreamInterface
        {
            return $this->appendFilterClass(MapIterator::class, $callable);
        }

        public function sort(
            int $direction = SortDirectionEnum::ASCENDING,
            bool $preserveKeys = false
        ): CollectionStreamInterface {
            return $this->appendFilterClass(SortIterator::class, $direction, $preserveKeys);
        }

        public function sortKeys(
            int $direction = SortDirectionEnum::ASCENDING
        ): CollectionStreamInterface {
            return $this->appendFilterClass(SortKeysIterator::class, $direction);
        }

        public function sortBy($callable, bool $preserveKeys = true): CollectionStreamInterface
        {
            return $this->appendFilterClass(SortByCallableIterator::class, $preserveKeys);
        }

        public function sortByField(
            string $field,
            int $direction = SortDirectionEnum::ASCENDING,
            bool $preserveKeys = false
        ): CollectionStreamInterface{
            return $this->appendFilterClass(SortByFieldIterator::class, $field, $direction, $preserveKeys);
        }
    }
