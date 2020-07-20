<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_;

    use PsychoB\WebFramework\Container\Container;
    use PsychoB\WebFramework\Container\ContainerInterface;
    use PsychoB\WebFramework\Debug\Clock;
    use PsychoB\WebFramework\Debug\TimeBus;
    use PsychoB\WebFramework\DependencyInjector\DynamicInjector;
    use PsychoB\WebFramework\Environment\ApplicationInterface;
    use PsychoB\WebFramework\Environment\EnvironmentInterface;
    use PsychoB\WebFramework\Injector\Injector;
    use PsychoB\WebFramework\Injector\InjectorInterface;
    use PsychoB\WebFramework\Web_\Route\RouteManager;

    class WebApplication implements ApplicationInterface
    {
        protected ContainerInterface $container;
        protected DynamicInjector $injector;
        protected string $appDirectory;
        protected string $appNamespace;
        protected Clock $timer;

        public function __construct(EnvironmentInterface $environment, string $appDirectory, string $appNamespace)
        {
            $this->container = new Container();
            $injector = new Injector($this->container);

            $this->container->set(InjectorInterface::class, $injector);
            $this->container->set(Injector::class, $injector);

            $this->container->set(EnvironmentInterface::class, $environment);
            $this->container->set(WebEnvironment::class, $environment);

            $this->container->set(ApplicationInterface::class, $this);
            $this->container->set(WebApplication::class, $this);

            $this->injector = new DynamicInjector($this->container, $injector);
            $this->appDirectory = $appDirectory;
            $this->appNamespace = $appNamespace;
            $this->timer = new Clock($this->injector->fetch(TimeBus::class), self::class);
        }

        public function run()
        {
            $_ = $this->timer->section('Run Application');

            /** @var RouteManager $routeManager */
            $routeManager = $this->injector->fetch(RouteManager::class);

            /** @noinspection GlobalVariableUsageInspection */
            return $routeManager->execute(
                $_SERVER['REQUEST_METHOD'],
                $_SERVER['REQUEST_URI']
            );
        }

        public function getAppDirectory(): string
        {
            return $this->appDirectory;
        }

        public function getAppNamespace(): string
        {
            return $this->appNamespace;
        }

        public function fetch(string $klass): object
        {
            return $this->injector->fetch($klass);
        }
    }
