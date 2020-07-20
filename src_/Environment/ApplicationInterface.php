<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Environment;

    interface ApplicationInterface
    {
        public function run();
        public function getAppDirectory(): string;
        public function getAppNamespace(): string;
        public function fetch(string $klass): object;
    }
