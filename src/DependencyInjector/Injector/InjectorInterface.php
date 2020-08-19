<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Injector;

    interface InjectorInterface
    {
        public function inject($callable, array $arguments = []);
        public function make(string $class, array $arguments = []): object;
    }
