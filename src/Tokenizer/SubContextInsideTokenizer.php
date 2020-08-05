<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer;

    use PsychoB\WebFramework\Tokenizer\Tokens\AbstractToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\SubContextCloseToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\SubContextOpenToken;
    use PsychoB\WebFramework\Tokenizer\Tokens\TokenInterface;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Str;

    class SubContextInsideTokenizer implements TokenizerInterface
    {
        /** @var SubContextGroup[] */
        private array $subContext;

        private string $outsideClass;

        /**
         * SubContextInsideTokenizer constructor.
         *
         * @param SubContextGroup[] $subContext
         * @param string            $outsideClass
         */
        public function __construct(array $subContext, string $outsideClass)
        {
            $this->subContext = $subContext;
            $this->outsideClass = $outsideClass;
        }

        public function tokenize(string $str): iterable
        {
            [$startingTags, $startingTagsMap, $endingTags, $endingTagsMap, $names, $typedStartTags, $typedEndTags] = $this->getOpenAndCloseTags();
            $it = 0;
            $len = Str::len($str);

            while ($it < $len) {
                $nextIt = Str::findNextOf($str, $startingTags, $it, $len);

                if ($nextIt === null) {
                    break;
                }

                if ($nextIt > $it) {
                    // produce outside token
                    yield new $this->outsideClass(Str::sub($str, $it, $nextIt - $it), $it);
                }

                $it = $nextIt;
                $nextMatch = Str::matchNextCharacter($str, $startingTags, $it);
                if (!Arr::hasKey($startingTagsMap, $nextMatch)) {
                    throw new InvalidFormatException();
                }

                $nextTokenizer = $startingTagsMap[$nextMatch];
                /** @var TokenizerInterface $tokenizer */
                $tokenizer = $names[$nextTokenizer];
                yield new SubContextOpenToken($nextMatch, $it, $nextTokenizer);
                $it += Str::len($nextMatch);

                if ($tokenizer->isSingleConsuming()) {
                    // we just skip to one of the closing brackets
                    $lastPos = Str::findNextOf($str, $typedEndTags[$nextTokenizer], $it, $len);
                    if ($lastPos === null) {
                        throw new InvalidFormatException();
                    }

                    /** @var TokenInterface|AbstractToken $token */
                    $token = Arr::first($tokenizer->tokenize(Str::sub($str, $it, $lastPos - $it)));
                    yield $token->withAdjustedStart($it);

                    $it += $token->getLength();
                } else
                {
                    $offset = 0;

                    /** @var TokenInterface|AbstractToken $token */
                    foreach ($tokenizer->tokenize(Str::sub($str, $it, $len)) as $token) {
                        yield $token->withAdjustedStart($it);

                        $offset = $token->getStart() + $token->getLength();
                        $nextEndToken = Str::matchNextCharacter($str, $typedEndTags[$nextTokenizer], $it + $offset);
                        if ($nextEndToken !== null) {
                            break;
                        }
                    }

                    $it += $offset;
                }

                $nextMatch = Str::matchNextCharacter($str, $typedEndTags[$nextTokenizer], $it);
                if (!Arr::hasKey($endingTagsMap, $nextMatch)) {
                    throw new InvalidFormatException();
                }
                yield new SubContextCloseToken($nextMatch, $it, $nextTokenizer);
                $it += Str::len($nextMatch);
            }

            if ($it < $len) {
                yield new $this->outsideClass(Str::sub($str, $it), $it);
            }
        }

        private function getOpenAndCloseTags(): array
        {
            $startTag = [];
            $endTag = [];
            $startTagMap = [];
            $endTagMap = [];
            $names = [];
            $typedStartTags = [];
            $typedEndTags = [];

            foreach ($this->subContext as $ctx) {
                $ctxName = $ctx->getName();
                $typedStartTags[$ctxName] = [];
                $typedEndTags[$ctxName] = [];

                foreach ($ctx->getStart() as $tag) {
                    $startTag[] = $tag;
                    $startTagMap[$tag] = $ctxName;
                    $typedStartTags[$ctxName][] = $tag;
                }

                foreach ($ctx->getEnd() as $tag) {
                    $endTag[] = $tag;
                    $endTagMap[$tag] = $ctxName;
                    $typedEndTags[$ctxName][] = $tag;
                }

                $names[$ctxName] = $ctx->getTokenizer();
            }

            return [$startTag, $startTagMap, $endTag, $endTagMap, $names, $typedStartTags, $typedEndTags];
        }

        public function isSingleConsuming(): bool
        {
            return false;
        }
    }
