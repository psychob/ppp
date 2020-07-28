<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer;

    use PsychoB\WebFramework\Utility\Arr;

    class ElementGroup
    {
        private string $name;
        private array $elements;
        private bool $mergeSimilar;
        private string $class;

        public function __construct(string $name, array $elements, bool $mergeSimilar, string $class)
        {
            $this->name = $name;
            $this->elements = $elements;
            $this->mergeSimilar = $mergeSimilar;
            $this->class = $class;
        }

        public function getName(): string
        {
            return $this->name;
        }

        /** @return string[] */
        public function getElements(): array
        {
            return $this->elements;
        }

        public function isMergeSimilar(): bool
        {
            return $this->mergeSimilar;
        }

        public function isDefault(): bool
        {
            return Arr::len($this->elements) === 0;
        }

        public function getClass(): string
        {
            return $this->class;
        }
    }
