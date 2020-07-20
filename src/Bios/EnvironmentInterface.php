<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Bios;

    interface EnvironmentInterface
    {
        public static function getLikelihoodOfCurrentEnvironment(): int;

        public function execute(callable $onLoad);
    }
