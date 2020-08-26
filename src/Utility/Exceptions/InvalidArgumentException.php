<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility\Exceptions;

    use PsychoB\WebFramework\Core\BaseException;
    use Throwable;

    class InvalidArgumentException extends BaseException
    {
        private $value;
        private ?string $name;
        private array $types;

        public function __construct($value, ?string $name, array $types, string $message = '', ?Throwable $previous = null)
        {
            $this->value = $value;
            $this->name = $name;
            $this->types = $types;

            parent::__construct($message, 0, $previous);
        }

        /**
         * @return mixed
         */
        public function getValue()
        {
            return $this->value;
        }

        /**
         * @return string|null
         */
        public function getName(): ?string
        {
            return $this->name;
        }

        /**
         * @return array
         */
        public function getTypes(): array
        {
            return $this->types;
        }
    }
