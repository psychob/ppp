<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Injector;

    interface InjectorInterface
    {
        public function make(string $class): object;

        /**
         * @param callable|string $callable
         * @param array           $arguments
         *
         * @return mixed
         */
        public function inject($callable, array $arguments = []);
    }
