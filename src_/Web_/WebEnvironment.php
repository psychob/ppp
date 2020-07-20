<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_;

    use PsychoB\WebFramework\Environment\EnvironmentInterface;

    class WebEnvironment implements EnvironmentInterface
    {
        protected string $projectDirectory;
        protected string $projectNamespace;
        protected WebApplication $app;

        public function __construct(string $projectDirectory, string $projectNamespace)
        {
            $this->projectDirectory = $projectDirectory;
            $this->projectNamespace = $projectNamespace;
        }

        public function run(callable $onBoot)
        {
            $this->app = new WebApplication($this, $this->projectDirectory, $this->projectNamespace);
            return $onBoot($this->app);
        }
    }
