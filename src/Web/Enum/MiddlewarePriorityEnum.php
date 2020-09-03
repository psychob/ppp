<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Enum;

    use PsychoB\WebFramework\Core\Enum\AbstractEnum;

    class MiddlewarePriorityEnum extends AbstractEnum
    {
        public const HIGHEST = 1000;
        public const HIGH = 100;
        public const NORMAL = 0;
        public const LOW = -100;
        public const LOWEST = -1000;
    }
