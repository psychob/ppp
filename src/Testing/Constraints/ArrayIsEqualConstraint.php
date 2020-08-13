<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Testing\Constraints;

    use PHPUnit\Framework\Constraint\Constraint;
    use PsychoB\WebFramework\Utility\Arr;
    use SebastianBergmann\Diff\Differ;
    use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

    class ArrayIsEqualConstraint extends Constraint
    {
        private array $expected;
        private array $matched = [];

        public function __construct(array $expected)
        {
            $this->expected = $expected;
        }

        public function toString(): string
        {
            return 'is equal to ' . $this->exporter()->export($this->expected);
        }

        protected function matches($other): bool
        {
            $processedKeys = [];
            $this->matched = Arr::toArray($other);

            foreach ($this->matched as $key => $value) {
                if (!array_key_exists($key, $this->expected)) {
                    return false;
                }

                if ($this->expected[$key] !== $value) {
                    return false;
                }

                $processedKeys[] = $key;
            }

            $missingKeyCount = Arr::len(Arr::keys($this->expected)) - Arr::len($processedKeys);
            if ($missingKeyCount > 0) {
                return false;
            }

            return true;
        }

        public function count(): int
        {
            return 1 + Arr::len($this->expected);
        }

        protected function failureDescription($other): string
        {
            return parent::failureDescription($this->matched);
        }

        protected function additionalFailureDescription($other): string
        {
            $differ = new Differ(new UnifiedDiffOutputBuilder("\n--- Expected\n+++ Actual\n"));
            return $differ->diff(var_export($this->expected, true), var_export($this->matched, true));
        }
    }
