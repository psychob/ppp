<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Exceptions;

    class UnknownApplicationHintException extends InjectionException
    {
        protected string $hint;

        public function __construct(string $hint, string $message = '', \Throwable $previous = null)
        {
            $this->hint = $hint;

            parent::__construct($message, 0, $previous);
        }

        /**
         * @return string
         */
        public function getHint(): string
        {
            return $this->hint;
        }
    }
