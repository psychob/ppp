<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route\Builder;

    interface RouteBuilderGroupInterface extends RouteBuilderInterface
    {
        public function middleware(string $aliasOrClass, ...$arguments): self;
        public function routes(callable $definitions): void;
    }
