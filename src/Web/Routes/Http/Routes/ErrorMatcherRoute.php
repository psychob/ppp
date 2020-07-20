<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http\Routes;

    use PsychoB\WebFramework\Web\Routes\Route;
    use PsychoB\WebFramework\Web\Routes\RouteMatcher;

    class ErrorMatcherRoute extends RouteMatcher
    {
        private ErrorRoute $route;

        public function __construct(string $uri)
        {
            parent::__construct($uri, [], new ErrorRoute());
        }
    }
