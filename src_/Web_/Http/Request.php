<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Http;

    use PsychoB\WebFramework\Collection\Opt;
    use PsychoB\WebFramework\Utils\Arr;
    use PsychoB\WebFramework\Web_\Http\Headers\Header;
    use PsychoB\WebFramework\Web_\Http\Headers\Container;

    class Request
    {
        protected string $method;
        protected string $uri;

        protected array $get;
        protected array $post;

        protected Container $headers;

        /**
         * Request constructor.
         *
         * @param string $method
         * @param string $uri
         * @param array  $get
         * @param array  $post
         * @param array|Container
         */
        public function __construct(string $method, string $uri, array $get, array $post, Container $headers)
        {
            $this->method = $method;
            $this->uri = $uri;
            $this->get = $get;
            $this->post = $post;
            $this->headers = $headers;
        }

        /** @noinspection GlobalVariableUsageInspection */
        public static function fromEnvironment(string $method, string $uri): self
        {
            return new Request(
                $method,
                $uri,
                $_GET,
                $_POST,
                Container::fromArray(collect($_SERVER)
                    ->filter(fn($val, $key) => strpos(strval($key), 'HTTP_') !== 0)
                    ->map(function ($value, &$key): string {
                        $key = substr($key, 5);
                        $key = str_replace('_', ' ', $key);
                        $key = strtolower($key);
                        $key = ucwords($key);
                        $key = str_replace(' ', '-', $key);

                        return $value;
                    }, Opt::MAP_REWRITE_KEYS)
                    ->toArray()
                ),
            );
        }

    }
