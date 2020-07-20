<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Http;

    use PsychoB\WebFramework\Web_\Http\Headers\Container as HeaderContainer;

    class Response
    {
        public const HTTP_CONTINUE            = 100;
        public const HTTP_SWITCHING_PROTOCOLS = 101;
        public const HTTP_PROCESSING          = 102;

        public const HTTP_OK                            = 200;
        public const HTTP_CREATED                       = 201;
        public const HTTP_ACCEPTED                      = 202;
        public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
        public const HTTP_NO_CONTENT                    = 204;
        public const HTTP_RESET_CONTENT                 = 205;
        public const HTTP_PARTIAL_CONTENT               = 206;
        public const HTTP_MULTI_STATUS                  = 207;
        public const HTTP_ALREADY_REPORTED              = 208;
        public const HTTP_IM_USED                       = 226;

        public const HTTP_MULTIPLE_CHOICES   = 300;
        public const HTTP_MOVED_PERMANENTLY  = 301;
        public const HTTP_FOUND              = 302;
        public const HTTP_SEE_OTHER          = 303;
        public const HTTP_NOT_MODIFIED       = 304;
        public const HTTP_USE_PROXY          = 305;
        public const HTTP_TEMPORARY_REDIRECT = 307;
        public const HTTP_PERMANENT_REDIRECT = 308;

        private $content;
        private int $code;
        private HeaderContainer $headers;

        /**
         * Response constructor.
         *
         * @param string $content
         * @param int    $code
         * @param array  $headers
         */
        public function __construct(string $content = '', int $code = self::HTTP_OK, array $headers = [])
        {
            $this->content = $content;
            $this->code = $code;
            $this->headers = HeaderContainer::fromArray($headers);
        }

        public function getStatusCode(): int
        {
            return $this->code;
        }
    }
