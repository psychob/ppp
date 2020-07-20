<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector;

    use Psr\Container\ContainerInterface;

    interface ReadonlyServiceContainerInterface
    {
        public function has(string $key): bool;
        public function get(string $key): object;
        public function getOr(string $key, $default = null);

        public function psr(): ContainerInterface;
    }
