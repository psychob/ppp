<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\Utility;

    use Exception;
    use LogicException;
    use PsychoB\WebFramework\Testing\TestCase;
    use PsychoB\WebFramework\Utility\Exceptions\NoMatchingException;
    use PsychoB\WebFramework\Utility\Fnc;
    use PsychoB\WebFramework\Utility\Exceptions\IteratorHasNoMoreElementsException;
    use Throwable;

    class FncTest extends TestCase
    {
        public function testRethrowSuccess(): void
        {
            $this->assertTrue(Fnc::rethrow(fn () => true, fn () => false));
        }

        public function testRethrowMatchingExactOneCallable(): void
        {
            $this->expectException(LogicException::class);

            Fnc::rethrow(
                function () { throw new Exception(); },
                fn (Exception $e) => new LogicException('', 0, $e)
            );
        }

        public function testRethrowMatchingAncestorOneCallable(): void
        {
            $this->expectException(LogicException::class);

            Fnc::rethrow(
                function () { throw new Exception(); },
                fn (Throwable $e) => new LogicException('', 0, $e)
            );
        }

        public function testRethrowNotmatchingOneCallable(): void
        {
            $this->expectException(NoMatchingException::class);

            Fnc::rethrow(
                function () { throw new Exception(); },
                fn (IteratorHasNoMoreElementsException $e) => null
            );
        }
    }
