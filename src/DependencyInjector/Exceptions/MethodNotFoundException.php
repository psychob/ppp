<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Exceptions;

    use Throwable;

    class MethodNotFoundException extends InjectionException
    {
        protected string $class;
        protected string $method;

        public function __construct(string $method, string $class, string $message = '', Throwable $previous = null)
        {
            $this->class = $class;
            $this->method = $method;

            parent::__construct($message, 0, $previous);
        }

        /**
         * @return string
         */
        public function getClass(): string
        {
            return $this->class;
        }

        /**
         * @return string
         */
        public function getMethod(): string
        {
            return $this->method;
        }
    }
