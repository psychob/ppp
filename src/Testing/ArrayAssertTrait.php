<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Testing;

    use Iterator;
    use PHPUnit\Framework\TestCase as PhpUnitTestCase;
    use PsychoB\WebFramework\Testing\Constraints\ArrayIsEqualConstraint;
    use PsychoB\WebFramework\Testing\Constraints\ArrayValuesAreEqualsConstraint;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Exceptions\IteratorHasNoMoreElementsException;
    use PsychoB\WebFramework\Utility\Str;
    use SebastianBergmann\Diff\Differ;
    use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

    /**
     * @mixin PhpUnitTestCase
     */
    trait ArrayAssertTrait
    {
        protected function assertArrayInstanceOf(array $expected, array $actual): void
        {
            try {
                foreach (Arr::tieIterValStrict($expected, $actual) as $item) {
                    [$e, $a] = $item;

                    $this->assertInstanceOf($e, $a, $this->ppp_internal__GetArrayDiff(
                        'assertArrayInstanceOf: Element type is not equal',
                        $expected,
                        $actual,
                        null,
                        fn($actual): string => get_class($actual)
                    ));
                }
            } catch (IteratorHasNoMoreElementsException $e) {
                $this->fail(
                    $this->ppp_internal__GetArrayDiff(
                        'assertArrayInstanceOf: Array\'s are not equal',
                        $expected,
                        $actual,
                        null,
                        fn($actual): string => get_class($actual)
                    )
                );
            }

            $this->assertCount(count($expected), $actual);
        }

        private function ppp_internal__GetArrayDiff(
            string $message,
            array $expected,
            array $actual,
            ?callable $expectedMap = null,
            ?callable $actualMap = null
        ): string {
            $ret = $message;
            $ret .= sprintf("\nDiff:\n");
            $expectedMap = $expectedMap ?? fn ($val) => $val;
            $actualMap = $actualMap ?? fn ($val) => $val;

            $expectedAsString = '';
            $actualAsString = '';

            foreach ($expected as $val) {
                $v = $expectedMap($val);

                $expectedAsString .= sprintf("    %s\n", $v);
            }

            foreach ($actual as $val) {
                $v = $actualMap($val);

                $actualAsString .= sprintf("    %s\n", $v);
            }

            $expectedAsString = sprintf("[\n%s\n]", Str::rtrim($expectedAsString));
            $actualAsString = sprintf("[\n%s\n]", Str::rtrim($actualAsString));

            $differ = new Differ(new UnifiedDiffOutputBuilder("\n--- Expected\n+++ Actual\n"));
            return $differ->diff($expectedAsString, $actualAsString);
        }

        public static function assertArrayHasKeys(array $expected, $actual, string $message = ''): void
        {
            if ($actual instanceof Iterator) {
                $actual = Arr::toArray($actual);
            }

            foreach ($actual as $key => $value) {
                PhpUnitTestCase::assertContains($key, $expected);
            }

            PhpUnitTestCase::assertGreaterThanOrEqual(count($expected), count($actual));
        }

        public static function assertArrayEquals(array $expected, $actual, string $message = ''): void
        {
            PhpUnitTestCase::assertThat($actual, new ArrayIsEqualConstraint($expected), $message);
        }

        public static function assertArrayValuesAreEquals(array $expected, $actual, string $message = ''): void
        {
            PhpUnitTestCase::assertThat(
                $actual,
                new ArrayValuesAreEqualsConstraint($expected),
                $message
            );
        }
    }
