<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route;

    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Fnc;
    use PsychoB\WebFramework\Utility\Str;
    use PsychoB\WebFramework\Web\Enum\HttpMethodEnum;
    use PsychoB\WebFramework\Web\Exceptions\UnknownHttpMethodException;
    use PsychoB\WebFramework\Web\Http\Request;

    class Route
    {
        protected array $methods;
        protected string $uri;
        protected array $controller;
        protected ?string $name = null;

        public function __construct(array $methods, string $uri, array $controller, ?string $name = null)
        {
            Fnc::assert(
                HttpMethodEnum::hasAll($methods),
                fn () => new UnknownHttpMethodException($methods)
            );

            $this->methods = $methods;
            $this->uri = $uri;
            $this->controller = $controller;
            $this->name = $name;
        }

        public function getMethods(): array
        {
            return $this->methods;
        }

        public function getUri(): string
        {
            return $this->uri;
        }

        public function getController(): array
        {
            return $this->controller;
        }

        public function getName(): ?string
        {
            return $this->name;
        }

        public function match(Request $request): ?FilledRoute
        {
            if (Arr::empty(Arr::valuesIntersects($this->methods, [$request->getMethod()]))) {
                return null;
            }

            $match = $this->patternMatch($request->getUri());
            if (Arr::empty($match)) {
                return null;
            }

            return new FilledRoute($this, $request, $match);
        }

        private bool $haveCompiledPattern = false;
        private string $compiledPattern = '';

        private function patternMatch(string $uri): array
        {
            if (!$this->haveCompiledPattern) {
                $this->compilePattern();
            }

            if (preg_match($this->compiledPattern, $uri, $m) === 1) {
                return $m;
            }

            return [];
        }

        private function compilePattern(): void
        {
            $pattern = '';
            $text = $this->uri;
            $len = Str::len($text);

            // first we need to check if we have any special character in our string
            $next = Str::findNext($text, '{');
            if ($next === null) {
                $pattern = preg_quote($text, '/');
            } else {
                // if not, then we need to parse rest of the string

                $it = 0;
                do {
                    $pattern .= preg_quote(Str::sub($text, $it, $next - $it), '/');

                    $paramIt = Str::findNext($text, '}', $next);
                    $param = Str::sub($text, $next + 1, $paramIt - $next - 1);

                    $pattern .= $this->compileParameter($param);

                    $it = $paramIt + 1;
                } while ($next = Str::findNext($text, '{', $it));

                if ($it < $len) {
                    $pattern .= preg_quote(Str::sub($text, $it), '/');
                }
            }

            $this->compiledPattern = sprintf('/^%s$/', $pattern);
            $this->haveCompiledPattern = true;
        }

        private function compileParameter(string $param): string
        {
            $param = Str::trim($param);
            $elements = Str::explode($param, ':');

            // options
            $paramName = '';
            $typeName = 'string';
            $optional = false;

            switch (Arr::len($elements)) {
                case 2:
                    $typeName = Str::trim($elements[1]);
                case 1:
                    $paramName = Str::trim($elements[0]);

                    if (Str::last($paramName) === '?') {
                        $optional = true;
                        $paramName = Str::rtrim($paramName, '?');
                    }
            }

            $pattern = '(?P<' . $paramName . '>';

            if ($optional) {
                $pattern .= '(?:|';
            }

            switch ($typeName) {
                case 'int':
                    $pattern .= '[0-9]+';
                    break;

                case 'uuid':
                    // f69208a9-3151-4d38-b4d5-5292eed5f9f2
                    $pattern .= '(?:[a-fA-F0-9]{8})-(?:(?:[a-fA-F0-9]{4})-){3}(?:[a-fA-F0-9]{12})';
                    break;

                case 'any':
                    $pattern .= '.*?';
                    break;

                default:
                case 'string':
                    $pattern .= '[^\\/]+';
            }

            if ($optional) {
                $pattern .= ')';
            }

            $pattern .= ')';

            return $pattern;
        }
    }
