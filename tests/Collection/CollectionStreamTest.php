<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\Collection;

    use PsychoB\WebFramework\Testing\TestCase;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Str;

    class CollectionStreamTest extends TestCase
    {
        public function provideFilters(): array
        {
            $input = [
                'aab' => 'AAB',
                'AcB' => 'aCb',
                'foo' => 'BAR',
                'Acb' => null,
                'BAR' => 'foo',
            ];

            $isUpperCallable = fn($val) => !Str::isUpper(Str::first($val ?? '') ?? '');
            $filterKeyOutput = [
                'aab' => 'AAB',
                'foo' => 'BAR',
            ];
            $filterValueOutput = [
                'AcB' => 'aCb',
                'BAR' => 'foo',
            ];
            $filterOutEmpty = [
                'aab' => 'AAB',
                'AcB' => 'aCb',
                'foo' => 'BAR',
                'BAR' => 'foo',
            ];
            $mapKey = [
                'aab1' => 'AAB',
                'AcB1' => 'aCb',
                'foo1' => 'BAR',
                'Acb1' => null,
                'BAR1' => 'foo',
            ];
            $mapValue = [
                'aab' => null,
                'AcB' => null,
                'foo' => null,
                'Acb' => null,
                'BAR' => null,
            ];

            return [
                [$input, $filterKeyOutput, 'filterKey', $isUpperCallable],
                [$input, $filterValueOutput, 'filterValue', $isUpperCallable],
                [$input, $filterOutEmpty, 'filterOutEmpty', fn () => true],
                [$input, $mapKey, 'mapKey', fn ($key) => $key . '1'],
                [$input, $mapValue, 'mapValue', fn ($value) => null],
            ];
        }

        /** @dataProvider provideFilters */
        public function testFilters(array $input, array $output, $method, callable $callable): void
        {
            $this->assertArrayEquals(
                $output,
                Arr::stream($input)->{$method}($callable),
                sprintf('Failed to use filter: %s', $method)
            );
        }

        public function testStackingIterators(): void
        {
            $this->assertArrayEquals(
                [ 'foo' => 'bar',],
                Arr::stream([
                    'aab' => 'AAB',
                    'AcB' => 'aCb',
                    'foo' => 'BAR',
                    'Acb' => null,
                    'BAR' => 'foo',
                ])
                   ->filterOutEmpty()
                   ->filterValue(fn ($value) => $value !== 'foo')
                   ->filterKey(fn ($key) => !Str::isUpper(Str::first($key)))
                   ->filterKey(fn ($key) => $key !== 'aab')
                   ->mapValue(fn ($value) => Str::toLower($value))
            );
        }

        public function testCachingBehaviour(): void
        {
            $stream = Arr::stream([
                'aab' => 'AAB',
                'AcB' => 'aCb',
                'foo' => 'BAR',
                'Acb' => null,
                'BAR' => 'foo',
            ]);

            $this->assertArrayEquals(
                [ 'foo' => 'bar',],
                $stream
                    ->filterOutEmpty()
                    ->filterValue(fn ($value) => $value !== 'foo')
                    ->filterKey(fn ($key) => !Str::isUpper(Str::first($key)))
                    ->filterKey(fn ($key) => $key !== 'aab')
                    ->mapValue(fn ($value) => Str::toLower($value))
            );

            $this->assertArrayEquals(
                [ 'foo' => 'bar',],
                $stream
            );
        }

        public function testCountElementsSimple(): void
        {
            $this->assertCount(5, Arr::stream([1, 2, 3, 4, 5]));
        }

        public function testCountElementComplex(): void
        {
            $stream = Arr::stream([1, 2, 3, 4, 5]);

            $this->assertCount(3, $stream->filterValue(fn ($value) => $value % 2 === 1));
            $this->assertCount(3, $stream);
        }
    }
