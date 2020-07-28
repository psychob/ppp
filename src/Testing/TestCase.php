<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Testing;

    use PHPUnit\Framework\TestCase as PhpUnitTestCase;

    class TestCase extends PhpUnitTestCase
    {
        use MatchExceptionTrait, ArrayExceptionTrait;
    }
