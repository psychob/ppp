<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Tokenizer;

    use PsychoB\WebFramework\Tokenizer\Tokens\TokenInterface;
    use PsychoB\WebFramework\Utility\Str;

    class Tokenizer
    {
        public static function create(): TokenizerBuilder
        {
            return new TokenizerBuilder();
        }

        /** @var ElementGroup[] */
        private array $elements = [];

        /**
         * @param ElementGroup[] $elements
         */
        public function __construct(array $elements)
        {
            $this->elements = $elements;
        }

        public function tokenize(string $str): array
        {
            $length = Str::len($str);

            $defaultType = $this->getDefaultType();
            $currentStr = '';
            $currentType = $defaultType;
            $currentStartIt = 0;
            [$mappedCharacters, $availableCharacters] = $this->getAllAvailableCharacters();
            $ret = [];

            for ($it = 0; $it < $length; ++$it) {
                $matchElement = Str::matchNextCharacter($str, $availableCharacters, $it);

                if ($matchElement === null) {
                    if ($defaultType === null) {
                        throw new UnexpectedCharacterException($str, $it);
                    }

                    if ($currentType !== $defaultType) {
                        $token = $this->pushToken($currentType, $currentStr, $currentStartIt);

                        if ($token !== null) {
                            $ret[] = $token;

                            $currentStr = $str[$it];
                            $currentType = $defaultType;
                            $currentStartIt = $it;
                        }
                    } else {
                        $currentStr .= $str[$it];
                    }
                } else {
                    $newType = $mappedCharacters[$matchElement];

                    if ($newType !== $currentType) {
                        $token = $this->pushToken($currentType, $currentStr, $currentStartIt);

                        if ($token !== null) {
                            $ret[] = $token;
                        }

                        $currentStr = $matchElement;
                        $currentType = $newType;
                        $currentStartIt = $it;
                    } else {
                        if ($this->getType($currentType)->isMergeSimilar()) {
                            $currentStr .= $matchElement;
                        } else {
                            $token = $this->pushToken($currentType, $currentStr, $currentStartIt);

                            if ($token !== null) {
                                $ret[] = $token;
                            }

                            $currentStr = $matchElement;
                            $currentType = $newType;
                            $currentStartIt = $it;
                        }
                    }

                    $it += Str::len($matchElement) - 1;
                }
            }

            if ($currentStr) {
                $token = $this->pushToken($currentType, $currentStr, $currentStartIt);

                if ($token) {
                    $ret[] = $token;
                }
            }

            return $ret;
        }

        private function getDefaultType(): ?string
        {
            foreach ($this->elements as $element) {
                if ($element->isDefault()) {
                    return $element->getName();
                }
            }

            return null;
        }

        private function getAllAvailableCharacters(): array
        {
            $mapped = [];
            $allChars = [];

            foreach ($this->elements as $element) {
                foreach ($element->getElements() as $chr) {
                    $allChars[] = $chr;
                    $mapped[$chr] = $element->getName();
                }
            }

            return [$mapped, $allChars];
        }

        private function getType(string $currentType): ElementGroup
        {
            foreach ($this->elements as $element) {
                if ($element->getName() == $currentType) {
                    return $element;
                }
            }
        }

        private function pushToken(?string $type, string $token, int $startIt): ?TokenInterface
        {
            if (empty($token)) {
                return null;
            }

            $element = $this->getType($type);
            $class = $element->getClass();

            return new $class($token, $startIt);
        }
    }
