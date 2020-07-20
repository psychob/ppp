<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Collection;

    interface CollectionInterface
    {
        public function filter($fnc): CollectionInterface;

        public function map($fnc, int $options = Opt::MAP_DEFAULT): CollectionInterface;

        public function toArray(): array;
    }
