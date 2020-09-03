<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route;

    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Web\Http\Request;

    class FilledRoute extends Route
    {
        private Request $request;
        private array $matched;

        public function __construct(Route $route, Request $request, array $matched = [])
        {
            parent::__construct($route->getMethods(), $route->getUri(), $route->getController(), $route->getName(), $route->getMiddlewares());

            $this->request = $request;
            $this->matched = Arr::stream($matched)->filterKey(fn ($v) => !is_int($v))->toArray();
        }

        /**
         * @return Request
         */
        public function getRequest(): Request
        {
            return $this->request;
        }

        /**
         * @return array
         */
        public function getMatched(): array
        {
            return $this->matched;
        }
    }
