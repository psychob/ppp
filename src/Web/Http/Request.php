<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http;

    class Request
    {
        protected string $method;
        protected string $uri;
        protected array $headers;

        /**
         * Request constructor.
         *
         * @param string $method
         * @param string $uri
         * @param array  $headers
         */
        public function __construct(string $method, string $uri, array $headers = [])
        {
            $this->method = $method;
            $this->uri = $uri;
            $this->headers = $headers;
        }

        public function getMethod(): string
        {
            return $this->method;
        }

        public function getUri(): string
        {
            return $this->uri;
        }

        /**
         * @return HeaderInterface[]
         */
        public function getHeaders(): array
        {
            return $this->headers;
        }
    }
