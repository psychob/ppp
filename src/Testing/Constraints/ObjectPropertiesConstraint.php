<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Testing\Constraints;

    use PHPUnit\Framework\Constraint\Constraint;
    use PsychoB\WebFramework\Utility\Arr;
    use SebastianBergmann\Comparator\ComparisonFailure;
    use SebastianBergmann\Comparator\Factory as ComparatorFactory;

    class ObjectPropertiesConstraint extends Constraint
    {
        private array $properties = [];
        private array $cachedOther = [];

        public function __construct(array $properties)
        {
            $this->properties = $properties;
        }

        public function count(): int
        {
            return Arr::len($this->properties);
        }

        protected function matches($other): bool
        {
            $this->cachedOther = [];

            foreach ($this->properties as $property => $value) {
                $this->cachedOther[$property] = $other->{$property}();

                if (!$this->isEqual($this->cachedOther[$property], $value)) {
                    return false;
                }
            }

            return true;
        }

        public function toString(): string
        {
            return 'has properties with values: ' . $this->exporter()->export($this->properties);
        }

        private function isEqual($left, $right): bool
        {
            if ($left === $right) {
                return true;
            }

            $comparatorFactory = ComparatorFactory::getInstance();

            try {
                $comparator = $comparatorFactory->getComparatorFor(
                    $left,
                    $right
                );

                $comparator->assertEquals(
                    $left,
                    $right
                );
            } catch (ComparisonFailure $f) {
                return false;
            }

            return true;
        }
    }
