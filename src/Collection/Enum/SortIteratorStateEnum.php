<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Enum;

    use PsychoB\WebFramework\Core\Enum\AbstractEnum;

    class SortIteratorStateEnum extends AbstractEnum
    {
        const INITIALIZED = 1;
        const SORTED      = 2;
    }
