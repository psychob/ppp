<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\DependencyInjector;

    interface InjectorInterface
    {
        public function inject($callable, array $arguments = []);

        public function make(string $class, array $arguments = []): object;
    }
