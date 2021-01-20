<?php
namespace CarloNicora\Minimalism\Services\Pools\Objects;

use CarloNicora\Minimalism\Interfaces\CacheInterface;
use CarloNicora\Minimalism\Interfaces\LoaderInterface;
use CarloNicora\Minimalism\Interfaces\ServiceInterface;

class Loader implements LoaderInterface
{
    /**
     * Loader constructor.
     * @param CacheInterface|null $cache
     * @param ServiceInterface|null $defaultService
     */
    public function __construct(
        protected ?CacheInterface $cache,
        protected ServiceInterface|null $defaultService,
    ) {}

    /**
     * @return ServiceInterface|null
     */
    public function getDefaultService(): ?ServiceInterface
    {
        return $this->defaultService;
    }

    /**
     * @return CacheInterface|null
     */
    public function getCacher(): ?CacheInterface
    {
        return $this->cache;
    }
}