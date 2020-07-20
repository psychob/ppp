<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes;

    class Route
    {
        private string $name;
        private array $method;
        private string $uri;
        private string $controller;
        private string $action;

        /**
         * Route constructor.
         *
         * @param string $name
         * @param array $method
         * @param string $uri
         * @param string $controller
         * @param string $action
         */
        public function __construct(string $name, array $method, string $uri, string $controller, string $action)
        {
            $this->name = $name;
            $this->method = $method;
            $this->uri = $uri;
            $this->controller = $controller;
            $this->action = $action;
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

        /**
         * @return array
         */
        public function getMethod(): array
        {
            return $this->method;
        }

        /**
         * @return string
         */
        public function getUri(): string
        {
            return $this->uri;
        }

        /**
         * @return string
         */
        public function getController(): string
        {
            return $this->controller;
        }

        /**
         * @return string
         */
        public function getAction(): string
        {
            return $this->action;
        }

        private bool $compiledPattern = false;
        private string $pattern = '';

        public function matchUri(string $uri): ?RouteMatcher
        {
            if (!$this->compiledPattern) {
                $this->compilePattern();
            }

            if (preg_match($this->pattern, $uri, $m)) {
                return new RouteMatcher($uri, $m, $this);
            }

            return null;
        }

        private function compilePattern(): void
        {
            $this->pattern = '/' . preg_quote($this->uri, '/') . '/';
            $this->compiledPattern = true;
        }
    }
