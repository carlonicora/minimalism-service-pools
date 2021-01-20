<?php
namespace CarloNicora\Minimalism\Service\Pools;

use CarloNicora\Minimalism\Interfaces\CacheInterface;
use CarloNicora\Minimalism\Interfaces\DataInterface;
use CarloNicora\Minimalism\Interfaces\DefaultServiceInterface;
use CarloNicora\Minimalism\Interfaces\ServiceInterface;
use CarloNicora\Minimalism\Service\Pools\Abstracts\AbstractDataLoader;
use CarloNicora\Minimalism\Service\Pools\Abstracts\AbstractResourceLoader;
use CarloNicora\Minimalism\Interfaces\LoaderInterface;
use CarloNicora\Minimalism\Service\Pools\Objects\Loader;
use CarloNicora\Minimalism\Services\JsonApi\JsonApi;
use Exception;
use RuntimeException;
use ReflectionClass;

class Pools implements ServiceInterface
{
    /** @var LoaderInterface  */
    private LoaderInterface $loader;

    /** @var array|null  */
    protected ?array $loaders=null;

    public function __construct(
        protected DataInterface $data,
        protected JsonApi $jsonApi,
        DefaultServiceInterface|ServiceInterface $defaultService,
        ?CacheInterface $cache=null,
    )
    {
        $loader = $defaultService->getLoaderInterface();
        if ($loader !== null) {
            $this->loader = $loader;
        } else {
            $this->loader = new Loader(
                cache: $cache,
                defaultService:$defaultService,
            );
        }
    }

    /**
     * @param string $className
     * @return LoaderInterface
     * @throws Exception
     */
    public function get(string $className): LoaderInterface
    {
        if (!array_key_exists($className, $this->loaders)) {
            $classType = new ReflectionClass($className);

            if ($classType->isSubclassOf(AbstractDataLoader::class)){
                $this->loaders[$className] = new $className(
                    loader: $this->loader,
                    data: $this->data,
                );
            } elseif ($classType->isSubclassOf(AbstractResourceLoader::class)) {
                $this->loaders[$className] = new $className(
                    loader: $this->loader,
                    jsonApi: $this->jsonApi,
                );
            } else {
                throw new RuntimeException('Loader Misconfigured', 500);
            }
        }

        return $this->loaders[$className];
    }

    /**
     *
     */
    public function initialise(): void
    {
        $this->loaders = [];
    }

    /**
     *
     */
    public function destroy(): void
    {
        $this->loaders = null;
    }
}