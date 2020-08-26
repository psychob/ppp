<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route;

    use PsychoB\WebFramework\Utility\Fnc;
    use PsychoB\WebFramework\Web\Enum\HttpMethodEnum;
    use PsychoB\WebFramework\Web\Exceptions\UnknownHttpMethodException;

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
    }
