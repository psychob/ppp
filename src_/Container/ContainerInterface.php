<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Container;

    interface ContainerInterface
    {
        public function get(string $id): object;

        public function set(string $id, object $value): void;

        public function has(string $id): bool;
    }
