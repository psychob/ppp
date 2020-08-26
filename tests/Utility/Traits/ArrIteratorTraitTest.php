<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\Utility\Traits;

    use ArrayIterator;
    use Mockery\MockInterface;
    use PsychoB\WebFramework\Testing\TestCase;
    use PsychoB\WebFramework\Utility\Enum\AssertPrimitiveTypeEnum;
    use PsychoB\WebFramework\Utility\Exceptions\InvalidArgumentException;
    use PsychoB\WebFramework\Utility\Traits\ArrIteratorTrait;
    use Iterator;

    class ArrIteratorTraitTest extends TestCase
    {
        /** @var ArrIteratorTrait|MockInterface */
        private $trait;

        protected function setUp(): void
        {
            parent::setUp();

            $this->trait = \Mockery::mock(ArrIteratorTrait::class)->makePartial();
        }

        public function testMapEmpty(): void
        {
            $this->assertArrayEquals(
                [],
                $this->trait->map([], fn ($v) => $v)
            );
        }

        public function testMapArray(): void
        {
            $this->assertArrayEquals(
                [2, 4, 6, 8, 10],
                $this->trait->map([1, 2, 3, 4, 5], fn ($v) => $v * 2)
            );
        }

        public function testMapIterator(): void
        {
            $this->assertArrayEquals(
                [2, 4, 6, 8, 10],
                $this->trait->map(new ArrayIterator([1, 2, 3, 4, 5]), fn ($v) => $v * 2)
            );
        }

        public function testMapFailedType(): void
        {
            $this->matchThrownException(function () {
                $this->trait->map(1, fn ($v) => $v);
            }, InvalidArgumentException::class, [
                'getValue' => 1,
                'getName' => '$arr',
                'getTypes' => [
                    Iterator::class,
                    AssertPrimitiveTypeEnum::ARRAY,
                ],
            ]);
        }

        public function testFilterEmpty(): void
        {
            $this->assertArrayEquals(
                [],
                $this->trait->filter([], fn ($v) => $v)
            );
        }

        public function testFilterArray(): void
        {
            $this->assertArrayValuesAreEquals(
                [2, 4, 6, 8, 10],
                $this->trait->filter([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], fn ($v) => $v % 2 === 0)
            );
        }

        public function testFilterIterator(): void
        {
            $this->assertArrayValuesAreEquals(
                [2, 4, 6, 8, 10],
                $this->trait->filter(new ArrayIterator([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]), fn ($v) => $v % 2 === 0)
            );
        }

        public function testFilterFailedType(): void
        {
            $this->matchThrownException(function () {
                $this->trait->filter(1, fn ($v) => $v);
            }, InvalidArgumentException::class, [
                'getValue' => 1,
                'getName' => '$arr',
                'getTypes' => [
                    Iterator::class,
                    AssertPrimitiveTypeEnum::ARRAY,
                ],
            ]);
        }
    }
