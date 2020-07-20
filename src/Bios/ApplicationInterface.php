<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Bios;

    interface ApplicationInterface
    {
        public function execute();
        public function make(string $class, array $arguments = []): object;

        public function getApplicationPath(): string;
        public function getFrameworkPath(): string;
    }
