<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector;

    interface ServiceContainerInterface extends ReadonlyServiceContainerInterface
    {
        public function set(string $key, object $value): void;
        public function register(array $elements): void;
    }
