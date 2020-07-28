<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\Tokenizer;

    use PsychoB\WebFramework\Testing\TestCase;
    use PsychoB\WebFramework\Tokenizer\Tokenizer;
    use PsychoB\WebFramework\Tokenizer\Tokens\LiteralToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\NumberToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\WhitespaceToken;

    class TokenizerTest extends TestCase
    {
        public function provideDefaultData(): array
        {
            return [
                ['', []], // empty data
                [" \r\n\t", [
                    WhitespaceToken::class,
                ]],
                [" foo ", [
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                ]],
                ["_ foo _", [
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                ]],
                ["_ foo _ 123 2588", [
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                ]],
            ];
        }

        public function provideAdvancedData(): array
        {
            return [
                ['', []], // empty data
                [" \r\n\t", [
                    WhitespaceToken::class,
                ]],
                [" foo ", [
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                ]],
                ["_ foo _", [
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                ]],
                ["_ foo _ 0x123 0b2588", [
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    NumberToken::class,
                    NumberToken::class,
                    WhitespaceToken::class,
                    NumberToken::class,
                    NumberToken::class,
                ]],
                ["0x123 0b2588", [
                    NumberToken::class,
                    NumberToken::class,
                    WhitespaceToken::class,
                    NumberToken::class,
                    NumberToken::class,
                ]],
                ["0x0x 0b2588", [
                    NumberToken::class,
                    NumberToken::class,
                    WhitespaceToken::class,
                    NumberToken::class,
                    NumberToken::class,
                ]],
            ];
        }

        /** @dataProvider provideDefaultData */
        public function testDefaultDataParse(string $str, array $tokens): void
        {
            $tokenizer = Tokenizer::create()
                                  ->addWhitespaceGroup()
                                  ->addLiteralGroup()
                                  ->make();

            $t = $tokenizer->tokenize($str);

            $this->assertArrayInstanceOf($tokens, $t);
        }

        /** @dataProvider provideAdvancedData */
        public function testAdvancedDataParse(string $str, array $tokens): void
        {
            $tokenizer = Tokenizer::create()
                                  ->addWhitespaceGroup()
                                  ->addLiteralGroup()
                                  ->addElementGroup('number', '0123456789', true, NumberToken::class)
                                  ->addElementGroup('number-header', ['0x', '0o', '0b'], false, NumberToken::class)
                                  ->make();

            $t = $tokenizer->tokenize($str);

            $this->assertArrayInstanceOf($tokens, $t);
        }
    }
