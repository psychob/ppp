<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Exceptions;

    use Psr\Container\ContainerExceptionInterface;
    use PsychoB\WebFramework\Core\BaseException;
    use Throwable;

    class FailedToFetchElementException extends BaseException implements ContainerExceptionInterface
    {
        protected string $element;

        /**
         * FailedToFetchElementException constructor.
         *
         * @param string $element
         * @param string $message
         * @param Throwable|null $previous
         */
        public function __construct(string $element, $message = 'Failure when fetching element', Throwable $previous = null)
        {
            parent::__construct($message, 0, $previous);

            $this->element = $element;
        }

        /**
         * @return string
         */
        public function getElement(): string
        {
            return $this->element;
        }
    }
