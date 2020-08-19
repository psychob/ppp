<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Exceptions;

    use PsychoB\WebFramework\Core\BaseException;
    use Throwable;

    class ElementNotFoundException extends BaseException
    {
        private array $elements;
        private string $key;

        /**
         * ElementNotFoundException constructor.
         *
         * @param array          $elements
         * @param string         $key
         * @param string         $message
         * @param Throwable|null $previous
         */
        public function __construct(array $elements, string $key, string $message = 'Element not found', Throwable $previous = null)
        {
            $this->elements = $elements;
            $this->key = $key;

            parent::__construct($message, 0, $previous);
        }

        /**
         * @return array
         */
        public function getElements(): array
        {
            return $this->elements;
        }

        /**
         * @return string
         */
        public function getKey(): string
        {
            return $this->key;
        }
    }
