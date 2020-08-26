<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Testing\Constraints;

    use PHPUnit\Framework\Constraint\Constraint;
    use PHPUnit\Framework\Constraint\IsEqual;
    use PsychoB\WebFramework\Utility\Arr;
    use SebastianBergmann\Diff\Differ;
    use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

    class ArrayValuesAreEqualsConstraint extends Constraint
    {
        private array $expected = [];

        public function __construct(array $expected)
        {
            $this->expected = $expected;
        }

        public function count(): int
        {
            return Arr::len($this->expected);
        }

        protected function matches($other): bool
        {
            foreach (Arr::tieIterValStrict($this->expected, $other) as $value) {
                [$first, $second] = $value;

                $isEq = new IsEqual($first);

                if (!$isEq->evaluate($second, '', true)) {
                    return false;
                }
            }

            return true;
        }

        public function toString(): string
        {
            return 'values are equal to ' . $this->exporter()->export($this->expected);
        }

        protected function additionalFailureDescription($other): string
        {
            $expectedStr = '[' . PHP_EOL;
            foreach ($this->expected as $value) {
                $expectedStr .= sprintf('    %s,%s', $this->exporter()->export($value, 1), PHP_EOL);
            }
            $expectedStr .= ']';

            $otherStr = '[' . PHP_EOL;
            foreach ($other as $value) {
                $otherStr .= sprintf('    %s,%s', $this->exporter()->export($value, 2), PHP_EOL);
            }
            $otherStr .= ']';
            $differ = new Differ(new UnifiedDiffOutputBuilder("\n--- Expected\n+++ Actual\n"));
            return $differ->diff($expectedStr, $otherStr);
        }
    }
