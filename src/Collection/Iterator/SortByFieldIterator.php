<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    use Iterator;
    use PsychoB\WebFramework\Collection\Enum\SortDirectionEnum;
    use PsychoB\WebFramework\Utility\Obj;

    class SortByFieldIterator extends SortByCallableIterator implements StreamIteratorInterface
    {
        public function __construct(Iterator $iterator, string $field, int $direction, bool $preserveKeys)
        {
            parent::__construct($iterator, function ($left, $right) use ($field, $direction): int {
                $leftValue = Obj::field($left, $field);
                $rightValue = Obj::field($right, $field);

                if ($direction === SortDirectionEnum::ASCENDING) {
                    return $leftValue <=> $rightValue;
                }

                return $rightValue <=> $leftValue;
            }, $preserveKeys);
        }
    }
