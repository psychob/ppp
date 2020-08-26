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
    use PsychoB\WebFramework\Utility\Traits\ArrIteratorTrait;

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

        public function testFirstEmptyArray(): void
        {
            $this->assertNull($this->trait->first([]));
        }

        public function testFirstEmptyIterator(): void
        {
            $this->assertNull($this->trait->first(new ArrayIterator([])));
        }

        public function testFirstEmptyGenerator(): void
        {
            $this->assertNull($this->trait->first((function () {
                return;
                yield 10;
            })()));
        }

        public function testFirstArray(): void
        {
            $this->assertSame(1, $this->trait->first([1, 2, 3]));
        }

        public function testFirstIterator(): void
        {
            $this->assertSame(1, $this->trait->first(new ArrayIterator([1, 2, 3])));
        }

        public function testFirstGenerator(): void
        {
            $this->assertSame(1, $this->trait->first((function () {
                yield 1;
                yield 2;
                yield 3;
            })()));
        }

        public function testLastEmptyArray(): void
        {
            $this->assertNull($this->trait->last([]));
        }

        public function testLastEmptyIterator(): void
        {
            $this->assertNull($this->trait->last(new ArrayIterator([])));
        }

        public function testLastEmptyGenerator(): void
        {
            $this->assertNull($this->trait->last((function () {
                return;
                yield 10;
            })()));
        }

        public function testLastArray(): void
        {
            $this->assertSame(3, $this->trait->last([1, 2, 3]));
        }

        public function testLastIterator(): void
        {
            $this->assertSame(3, $this->trait->last(new ArrayIterator([1, 2, 3])));
        }

        public function testLastGenerator(): void
        {
            $this->assertSame(3, $this->trait->last((function () {
                yield 1;
                yield 2;
                yield 3;
            })()));
        }

        public function testFirstOfEmptyArray(): void
        {
            $this->assertNull($this->trait->firstOf([], fn ($v) => $v % 2));
        }

        public function testFirstOfEmptyIterator(): void
        {
            $this->assertNull($this->trait->firstOf(new ArrayIterator([]), fn ($v) => $v % 2));
        }

        public function testFirstOfEmptyGenerator(): void
        {
            $this->assertNull($this->trait->firstOf((function () {
                return;
                yield 10;
            })(), fn ($v) => $v % 2));
        }

        public function testFirstOfArray(): void
        {
            $this->assertSame(2, $this->trait->firstOf([1, 2, 3], fn ($v) => ($v % 2) === 0));
        }

        public function testFirstOfIterator(): void
        {
            $this->assertSame(2, $this->trait->firstOf(new ArrayIterator([1, 2, 3]), fn ($v) => $v % 2 === 0));
        }

        public function testFirstOfGenerator(): void
        {
            $this->assertSame(2, $this->trait->firstOf((function () {
                yield 1;
                yield 2;
                yield 3;
            })(), fn ($v) => $v % 2 === 0));
        }

        public function testLastOfEmptyArray(): void
        {
            $this->assertNull($this->trait->lastOf([], fn ($v) => $v % 2 === 0));
        }

        public function testLastOfEmptyIterator(): void
        {
            $this->assertNull($this->trait->lastOf(new ArrayIterator([]), fn ($v) => $v % 2 === 0));
        }

        public function testLastOfEmptyGenerator(): void
        {
            $this->assertNull($this->trait->lastOf((function () {
                return;
                yield 10;
            })(), fn ($v) => $v % 2 === 0));
        }

        public function testLastOfArray(): void
        {
            $this->assertSame(4, $this->trait->lastOf([1, 2, 3, 4, 5], fn ($v) => $v % 2 === 0));
        }

        public function testLastOfIterator(): void
        {
            $this->assertSame(4, $this->trait->lastOf(new ArrayIterator([1, 2, 3, 4, 5]), fn ($v) => $v % 2 === 0));
        }

        public function testLastOfGenerator(): void
        {
            $this->assertSame(4, $this->trait->lastOf((function () {
                yield 1;
                yield 2;
                yield 3;
                yield 4;
                yield 5;
            })(), fn ($v) => $v % 2 === 0));
        }
    }
