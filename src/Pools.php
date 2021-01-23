<?php
namespace CarloNicora\Minimalism\Services\Pools;

use CarloNicora\Minimalism\Interfaces\CacheInterface;
use CarloNicora\Minimalism\Interfaces\DataInterface;
use CarloNicora\Minimalism\Interfaces\DataLoaderInterface;
use CarloNicora\Minimalism\Interfaces\ResourceLoaderInterface;
use CarloNicora\Minimalism\Interfaces\ServiceInterface;
use CarloNicora\Minimalism\Services\Pools\Abstracts\AbstractDataLoader;
use CarloNicora\Minimalism\Services\Pools\Abstracts\AbstractResourceLoader;
use CarloNicora\Minimalism\Interfaces\LoaderInterface;
use CarloNicora\Minimalism\Services\Pools\Objects\Loader;
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

    /**
     * Pools constructor.
     * @param DataInterface $data
     * @param JsonApi $jsonApi
     * @param CacheInterface|null $cache
     */
    public function __construct(
        protected DataInterface $data,
        protected JsonApi $jsonApi,
        ?CacheInterface $cache=null,
    )
    {
        $this->loaders = [];

        $this->loader = new Loader(
            cache: $cache,
            defaultService:null,
        );

        $this->jsonApi->setLoaderInterface($this->loader);
    }

    /**
     * @param LoaderInterface $loader
     */
    public function setSpecialisedLoader(LoaderInterface $loader): void
    {
        $this->loader = $loader;
        $this->jsonApi->setLoaderInterface($this->loader);

        /** @var AbstractDataLoader|AbstractResourceLoader $singleLoader */
        foreach ($this->loaders ?? [] as $singleLoader){
            $singleLoader->setLoader($this->loader);
        }
    }

    /**
     * @param string $className
     * @return DataLoaderInterface|ResourceLoaderInterface
     * @throws Exception
     */
    public function get(string $className): DataLoaderInterface|ResourceLoaderInterface
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