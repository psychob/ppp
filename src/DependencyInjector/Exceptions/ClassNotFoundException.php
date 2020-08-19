<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Exceptions;

    class ClassNotFoundException extends InjectionException
    {
        protected string $class;

        public function __construct(string $class, string $message = '', \Throwable $previous = null)
        {
            $this->class = $class;

            parent::__construct($message, 0, $previous);
        }

        public function getClass(): string
        {
            return $this->class;
        }
    }
