<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http;

    class Request
    {
        private string $method;
        private string $uri;
        private array $headers;
        private array $get;
        private array $post;
        private array $files;
        private ?string $body;

        /**
         * Request constructor.
         *
         * @param string $method
         * @param string $uri
         * @param array       $headers
         * @param array       $get
         * @param array       $post
         * @param array       $files
         * @param string|null $body
         */
        public function __construct(
            string $method,
            string $uri,
            array $headers,
            array $get,
            array $post,
            array $files,
            ?string $body
        )
        {
            $this->method = $method;
            $this->uri = $uri;
            $this->headers = $headers;
            $this->get = $get;
            $this->post = $post;
            $this->files = $files;
            $this->body = $body;
        }

        /**
         * @return string
         */
        public function getMethod(): string
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
         * @return array
         */
        public function getHeaders(): array
        {
            return $this->headers;
        }

        /**
         * @return array
         */
        public function getGet(): array
        {
            return $this->get;
        }

        /**
         * @return array
         */
        public function getPost(): array
        {
            return $this->post;
        }

        /**
         * @return array
         */
        public function getFiles(): array
        {
            return $this->files;
        }

        /**
         * @return string|null
         */
        public function getBody(): ?string
        {
            return $this->body;
        }
    }
