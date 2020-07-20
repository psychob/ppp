<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http;

    class RequestBuilder
    {
        private ?string $method = null;
        private ?string $uri = null;
        private array $headers = [];
        private array $get = [];
        private array $post = [];
        private array $files = [];
        private ?string $body = null;

        public static function new(): self
        {
            return new RequestBuilder();
        }

        public function method(string $method): self
        {
            $this->method = $method;
            return $this;
        }

        public function uri(string $uri): self
        {
            $this->uri = $uri;
            return $this;
        }

        public function headers(array $headers): self
        {
            $this->headers = $headers;
            return $this;
        }

        public function get(array $get): self
        {
            $this->get = $get;
            return $this;
        }

        public function post(array $post): self
        {
            $this->post = $post;
            return $this;
        }

        public function files(array $files): self
        {
            $this->files = $files;
            return $this;
        }

        public function body(string $body): self
        {
            $this->body = $body;
            return $this;
        }

        public function toRequest(): Request
        {
            return new Request($this->method, $this->uri, $this->headers, $this->get, $this->post, $this->files, $this->body);
        }
    }
