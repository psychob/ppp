<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Core;

    interface ApplicationInterface
    {
        public function execute();
        public function make(string $class, array $arguments = []): object;
        public function inject($callable, array $arguments = []);
    }
