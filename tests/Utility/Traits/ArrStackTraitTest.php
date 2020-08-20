<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\Utility\Traits;

    use Mockery\MockInterface;
    use PsychoB\WebFramework\Testing\TestCase;
    use PsychoB\WebFramework\Utility\Exceptions\EmptyStackException;
    use PsychoB\WebFramework\Utility\Traits\ArrStackTrait;

    class ArrStackTraitTest extends TestCase
    {
        /** @var ArrStackTrait|MockInterface */
        private $trait;

        protected function setUp(): void
        {
            parent::setUp();

            $this->trait = \Mockery::mock(ArrStackTrait::class)->makePartial();
        }

        public function testPopFrontWhenEmpty(): void
        {
            $arr = [];

            $this->assertNull($this->trait->popFront($arr));
            $this->assertEmpty($arr);
        }

        public function testPopFrontWhenOneElement(): void
        {
            $arr = [21];

            $this->assertSame(21, $this->trait->popFront($arr));
            $this->assertEmpty($arr);
        }

        public function testPopFrontWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->assertSame(21, $this->trait->popFront($arr));
            $this->assertEquals([32, 14, 88], $arr);
        }

        public function testPopBackWhenEmpty(): void
        {
            $arr = [];

            $this->assertNull($this->trait->popBack($arr));
            $this->assertEmpty($arr);
        }

        public function testPopBackWhenOneElement(): void
        {
            $arr = [21];

            $this->assertSame(21, $this->trait->popBack($arr));
            $this->assertEmpty($arr);
        }

        public function testPopBackWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->assertSame(88, $this->trait->popBack($arr));
            $this->assertEquals([21, 32, 14], $arr);
        }

        public function testFetchFrontWhenEmpty(): void
        {
            $arr = [];

            $this->assertNull($this->trait->fetchFront($arr));
            $this->assertEmpty($arr);
        }

        public function testFetchFrontWhenOneElement(): void
        {
            $arr = [21];

            $this->assertSame(21, $this->trait->fetchFront($arr));
            $this->assertEquals([21], $arr);
        }

        public function testFetchFrontWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->assertSame(21, $this->trait->fetchFront($arr));
            $this->assertEquals([21, 32, 14, 88], $arr);
        }

        public function testFetchBackWhenEmpty(): void
        {
            $arr = [];

            $this->assertNull($this->trait->fetchBack($arr));
            $this->assertEmpty($arr);
        }

        public function testFetchBackWhenOneElement(): void
        {
            $arr = [21];

            $this->assertSame(21, $this->trait->fetchBack($arr));
            $this->assertEquals([21], $arr);
        }

        public function testFetchBackWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->assertSame(88, $this->trait->fetchBack($arr));
            $this->assertEquals([21, 32, 14, 88], $arr);
        }

        public function testPushFrontWhenEmpty(): void
        {
            $arr = [];

            $this->trait->pushFront($arr, 21);
            $this->assertEquals([21], $arr);
        }

        public function testPushFrontWhenOneElement(): void
        {
            $arr = [21];

            $this->trait->pushFront($arr, 37);
            $this->assertEquals([37, 21], $arr);
        }

        public function testPushFrontWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->trait->pushFront($arr, 37);
            $this->assertEquals([37, 21, 32, 14, 88], $arr);
        }

        public function testPushBackWhenEmpty(): void
        {
            $arr = [];

            $this->trait->pushBack($arr, 21);
            $this->assertEquals([21], $arr);
        }

        public function testPushBackWhenOneElement(): void
        {
            $arr = [21];

            $this->trait->pushBack($arr, 37);
            $this->assertEquals([21, 37], $arr);
        }

        public function testPushBackWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->trait->pushBack($arr, 37);
            $this->assertEquals([21, 32, 14, 88, 37], $arr);
        }

        public function testEnsurePopFrontWhenEmpty(): void
        {
            $this->expectException(EmptyStackException::class);

            $arr = [];
            $this->trait->ensurePopFront($arr);
        }

        public function testEnsurePopFrontWhenOneElement(): void
        {
            $arr = [21];

            $this->assertEquals(21, $this->trait->ensurePopFront($arr));
            $this->assertEmpty($arr);
        }

        public function testEnsurePopFrontWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->assertEquals(21, $this->trait->ensurePopFront($arr));
            $this->assertEquals([32, 14, 88], $arr);
        }

        public function testEnsurePopBackWhenEmpty(): void
        {
            $this->expectException(EmptyStackException::class);

            $arr = [];
            $this->trait->ensurePopBack($arr);
        }

        public function testEnsurePopBackWhenOneElement(): void
        {
            $arr = [21];

            $this->assertEquals(21, $this->trait->ensurePopBack($arr));
            $this->assertEmpty($arr);
        }

        public function testEnsurePopBackWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->assertEquals(88, $this->trait->ensurePopBack($arr));
            $this->assertEquals([21, 32, 14], $arr);
        }

        public function testEnsureFetchFrontWhenEmpty(): void
        {
            $this->expectException(EmptyStackException::class);

            $arr = [];
            $this->trait->ensureFetchFront($arr);
        }

        public function testEnsureFetchFrontWhenOneElement(): void
        {
            $arr = [21];

            $this->assertEquals(21, $this->trait->ensureFetchFront($arr));
            $this->assertEquals([21], $arr);
        }

        public function testEnsureFetchFrontWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->assertEquals(21, $this->trait->ensureFetchFront($arr));
            $this->assertEquals([21, 32, 14, 88], $arr);
        }

        public function testEnsureFetchBackWhenEmpty(): void
        {
            $this->expectException(EmptyStackException::class);

            $arr = [];
            $this->trait->ensureFetchBack($arr);
        }

        public function testEnsureFetchBackWhenOneElement(): void
        {
            $arr = [21];

            $this->assertEquals(21, $this->trait->ensureFetchBack($arr));
            $this->assertEquals([21], $arr);
        }

        public function testEnsureFetchBackWhenMultipleElement(): void
        {
            $arr = [21, 32, 14, 88];

            $this->assertEquals(88, $this->trait->ensureFetchBack($arr));
            $this->assertEquals([21, 32, 14, 88], $arr);
        }
    }
