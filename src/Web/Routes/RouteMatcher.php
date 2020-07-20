<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes;

    class RouteMatcher
    {
        private string $uri;
        private array $matches;
        private Route $route;

        public function __construct(string $uri, array $matches, Route $route)
        {
            $this->uri = $uri;
            $this->matches = $matches;
            $this->route = $route;
        }

        /**
         * @return string
         */
        public function getUri(): string
        {
            return $this->uri;
        }

        /**
         * @return array
         */
        public function getMatches(): array
        {
            return $this->matches;
        }

        /**
         * @return Route
         */
        public function getRoute(): Route
        {
            return $this->route;
        }
    }
