<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Container;

    interface ServiceContainerInterface extends ReadonlyServiceContainerInterface
    {
        /**
         * Set $key to $value
         *
         * @param string $key
         * @param object $value
         */
        public function set(string $key, object $value): void;

        /**
         * Set multiple elements.
         *
         * @param array $elements Values must be passed as key => value pairs.
         */
        public function register(array $elements): void;
    }
