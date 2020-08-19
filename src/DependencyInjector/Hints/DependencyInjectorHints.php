<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Hints;

    interface DependencyInjectorHints
    {
        /**
         * Fetch dependency injection hints for this class.
         *
         * @return array
         * @internal
         */
        public static function ppp_internal__GetHints(): array;
    }
