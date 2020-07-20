<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\DynamicObject;

    class DynamicObjectBuilder
    {
        protected string $extends;
        protected array $properties;
        protected $passThroughF = null;

        public static function new(): self
        {
            return new DynamicObjectBuilder();
        }

        public function extends(string $class): self
        {
            $this->extends = $class;
            return $this;
        }

        public function createProperty(string $name, $value = null): self
        {
            $this->properties[$name] = $value;
            return $this;
        }

        public function passThroughFunction(callable $function): self
        {
            $this->passThroughF = $function;

            return $this;
        }

        public function make(): object
        {
            $code = 'return function () { return new class(...func_get_args())';

            if ($this->extends) {
                $code .= sprintf(' extends %s', $this->extends);
            }

            $code .= ' { '.PHP_EOL;
            foreach ($this->properties as $key => $value) {
                $code .= sprintf(' public $%s;'.PHP_EOL, $key);
            }
            $code .= sprintf(' private $passThrough;'.PHP_EOL);

            $code .= PHP_EOL.' public function __construct( ';

            foreach ($this->properties as $key => $value) {
                $code .= sprintf('$%s, ', $key);
            }

            if ($this->passThroughF) {
                $code .= sprintf(' $passThrough, ');
            }
            $code .= '$_ = null) {'.PHP_EOL;

            foreach ($this->properties as $key => $value) {
                $code .= sprintf('  $this->%s = $%s;'.PHP_EOL, $key, $key);
            }
            if ($this->passThroughF) {
                $code .= sprintf('  $this->passThrough = $passThrough;'.PHP_EOL);
            }

            $code .= ' }'. PHP_EOL;

            if ($this->extends) {
                $ref = new \ReflectionClass($this->extends);

                foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED) as $method) {
                    if ($method->getName() === '__construct') {
                        continue;
                    }

                    $code .= sprintf(' %s function %s(', $this->getModifier($method->getModifiers()), $method->getName());
                    // args
                    for ($it = 0; $it < $method->getNumberOfParameters(); ++$it) {
                        $refParam = $method->getParameters()[$it];

                        $code .= sprintf(' %s $%s', $refParam->hasType() ? $refParam->getType()->getName() : '', $refParam->getName());
                        if ($refParam->isDefaultValueAvailable()) {
                            $code .= sprintf(' = %s', var_export($refParam->getDefaultValue(), true));
                        }
                        if ($it < $method->getNumberOfParameters() - 1) {
                            $code .= ', ';
                        }
                    }
                    $code .= sprintf(')');
                    // return value
                    if ($method->hasReturnType()) {
                        $code .= sprintf(
                            ': %s%s ',
                            $method->getReturnType()->allowsNull() ? '?' : '',
                            $method->getReturnType()->getName()
                        );
                    }
                    $code .= sprintf('{'.PHP_EOL);
                    $code .= sprintf('   return ($this->passThrough)($this, "%s", func_get_args());'.PHP_EOL, $method->getName());
                    $code .= sprintf(' }'.PHP_EOL.PHP_EOL);
//                    dump($method);
                }
            } else {
                // TODO
            }

            $code .= '}; };';

            $fnc = eval($code);
            return $fnc(...(
                collect($this->properties)
                    ->recountKeys()
                    ->append($this->passThroughF)
                    ->toArray()
            ));
        }

        private function getModifier(int $mod): string
        {
            $ret = '';

            if ($mod & \ReflectionMethod::IS_STATIC) {
                $ret .= 'static ';
            }

            if ($mod & \ReflectionMethod::IS_PUBLIC) {
                $ret .= 'public ';
            }

            if ($mod & \ReflectionMethod::IS_PROTECTED) {
                $ret .= 'protected ';
            }

            return $ret;
        }
    }
