<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Route;

    use Ramsey\Uuid\Uuid;

    class Route
    {
        protected ?string $name = null;
        protected string $uuid;
        protected array $verbs = [];
        protected string $url = '';
        protected array $middleware = [];
        protected array $controller = [];
        protected string $compiledStr = '';

        /**
         * Route constructor.
         *
         * @param string|null $name
         * @param array       $verbs
         * @param string      $url
         * @param array       $middleware
         * @param array       $controller
         */
        public function __construct(
            ?string $name,
            array $verbs,
            string $url,
            array $middleware,
            array $controller
        ) {
            $this->name = $name;
            $this->uuid = Uuid::uuid4();
            $this->verbs = $verbs;
            $this->url = $url;
            $this->middleware = $middleware;
            $this->controller = $controller;
        }

        public static function fromArray(array $array): Route
        {
            return new Route(
                $array['name'],
                $array['verbs'],
                $array['url'],
                $array['middleware'],
                $array['ctrl'],
            );
        }

        public function match(string $method, string $uri): bool
        {
            if (!in_array($method, $this->verbs)) {
                return false;
            }

            if (!$this->compiledStr) {
                $this->compile();
            }

            return preg_match($this->compiledStr, $uri);
        }

        private function compile(): void
        {
            $this->compiledStr = '/'.$this->escapeStr($this->url).'/';
        }

        private function escapeStr(string $url): string
        {
            return str_replace([
                '/'
            ], [
                '\/',
            ], $url);
        }

        public function getControllerClass(): string
        {
            return $this->controller[0];
        }

        public function getControllerAction(): string
        {
            return $this->controller[1];
        }

        public function getMiddlewares(): array
        {
            return $this->middleware;
        }
    }
