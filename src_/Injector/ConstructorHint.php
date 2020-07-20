<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Injector;

    interface ConstructorHint
    {
        /** @internal */
        public static function _GetConstructorHint(): array;
    }
