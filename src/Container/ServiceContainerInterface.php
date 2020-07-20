<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Container;

    use Psr\Container\ContainerInterface as PsrContainerInterface;

    interface ServiceContainerInterface
    {
        public function get(string $class);

        public function getOr(string $class, $default);

        public function has(string $class): bool;

        public function set(string $class, $value): void;

        public function psr(): PsrContainerInterface;
    }
