<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Enum;

    use PsychoB\WebFramework\Core\Enum\AbstractEnum;

    class SortDirectionEnum extends AbstractEnum
    {
        public const ASCENDING = 1;
        public const DESCENDING = 2;
    }
