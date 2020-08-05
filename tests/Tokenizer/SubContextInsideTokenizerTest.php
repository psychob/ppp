<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\Tokenizer;

    use PsychoB\WebFramework\Testing\TestCase;
    use PsychoB\WebFramework\Tokenizer\SubContextInsideTokenizer;
    use PsychoB\WebFramework\Tokenizer\Tokenizer;
    use PsychoB\WebFramework\Tokenizer\TokenizerInterface;
    use PsychoB\WebFramework\Tokenizer\Tokens\LiteralToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\OutsideToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\SubContextCloseToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\SubContextOpenToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\WhitespaceToken;
    use PsychoB\WebFramework\Utility\Arr;

    class SubContextInsideTokenizerTest extends TestCase
    {
        public function provideDefaultData(): array
        {
            return [
                ['', []], // empty data
                ['{* comment *}', [
                    SubContextOpenToken::class,
                    LiteralToken::class,
                    SubContextCloseToken::class,
                ]],
                ['{{ expression }}', [
                    SubContextOpenToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    SubContextCloseToken::class,
                ]],
                ['{% block %}', [
                    SubContextOpenToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    SubContextCloseToken::class,
                ]],
                ['{* comment *} {{ expression }} {% block %}', [
                    SubContextOpenToken::class,
                    LiteralToken::class,
                    SubContextCloseToken::class,
                    OutsideToken::class,
                    SubContextOpenToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    SubContextCloseToken::class,
                    OutsideToken::class,
                    SubContextOpenToken::class,
                    WhitespaceToken::class,
                    LiteralToken::class,
                    WhitespaceToken::class,
                    SubContextCloseToken::class,
                ]],
            ];
        }

        /** @dataProvider provideDefaultData */
        public function testDefaultDataParse(string $str, array $tokens): void
        {
            $tokenizer = $this->getTokenizer();
            $this->assertInstanceOf(SubContextInsideTokenizer::class, $tokenizer);

            $t = $tokenizer->tokenize($str);

            $this->assertArrayInstanceOf($tokens, Arr::toArray($t));
        }

        private function getTokenizer(): TokenizerInterface
        {
            $basicTokenizer = Tokenizer::create()
                                  ->addLiteralGroup()
                                  ->addWhitespaceGroup()
                                  ->make();

            $literalTokenizer = Tokenizer::create()
                ->addLiteralGroup()
                ->make();

            return Tokenizer::create()
                            ->parseOutside(false, OutsideToken::class)
                            ->addSubContextParser('comment', '{*', '*}', $literalTokenizer)
                            ->addSubContextParser('expression', '{{', '}}', $basicTokenizer)
                            ->addSubContextParser('block', '{%', '%}', $basicTokenizer)
                            ->make();
        }
    }
