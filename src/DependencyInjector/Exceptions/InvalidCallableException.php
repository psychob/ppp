<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Exceptions;

    use PsychoB\WebFramework\Core\InvalidArgumentException;
    use Throwable;

    class InvalidCallableException extends InvalidArgumentException
    {
        protected $callable;

        public function __construct($callable, string $message = 'Invalid Callable', Throwable $previous = null)
        {
            $this->callable = $callable;

            parent::__construct($message, 0, $previous);
        }

        /**
         * @return mixed
         */
        public function getCallable()
        {
            return $this->callable;
        }
    }
