<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Config;

    use PsychoB\WebFramework\Debug\Clock;
    use PsychoB\WebFramework\Environment\ApplicationHints;
    use PsychoB\WebFramework\Injector\ConstructorHint;
    use PsychoB\WebFramework\Utils\Arr;

    class OptionsManager implements ConstructorHint
    {
        private array $cached = [];
        private array $loadedFiles = [];
        private string $path;
        private Clock $timer;

        public function __construct(string $kAppPath, Clock $clock)
        {
            $this->path = $kAppPath . DIRECTORY_SEPARATOR . 'config/options' . DIRECTORY_SEPARATOR;
            $this->timer = $clock;
        }

        public function get(string $key, $default = null)
        {
            $elements = explode('.', $key);
            $first = current($elements);

            if (!$this->isFileLoaded($first)) {
                $this->loadFile($first);
            }

            return $this->recursiveGet($this->cached, $elements, $default);
        }

        private function recursiveGet(array $cached, array $elements, $default)
        {
            if (Arr::empty($elements)) {
                return $cached;
            }

            $key = Arr::popFront($elements);

            if (!Arr::hasKey($cached, $key)) {
                return $default;
            }

            return $this->recursiveGet($cached[$key], $elements, $default);
        }

        private function isFileLoaded(string $first): bool
        {
            return in_array($first, $this->loadedFiles);
        }

        private function loadFile(string $first): void
        {
            $_ = $this->timer->section('Load configuration file: '. $first);

            if (file_exists($this->path . $first . '.php')) {
                $this->cached[$first] = includeTrampoline($this->path . $first . '.php');
            }

            $this->loadedFiles[] = $first;
        }

        public static function _GetConstructorHint(): array
        {
            return [
                'kAppPath' => ApplicationHints::APP_DIRECTORY,
            ];
        }
    }

    /**
     * @param string $file
     *
     * @return array
     * @internal
     */
    function includeTrampoline(string $file): array
    {
        /** @noinspection PhpIncludeInspection */
        return require_once $file;
    }
