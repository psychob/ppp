<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility\Exceptions;

    use PsychoB\WebFramework\Core\BaseException;

    class NoMatchingException extends BaseException
    {
        protected $response;

        public function __construct($response, string $message = '', \Throwable $previous = null)
        {
            $this->response = $response;

            parent::__construct($message, 0, $previous);
        }

        /**
         * @return mixed
         */
        public function getResponse()
        {
            return $this->response;
        }
    }
