<?php
namespace CarloNicora\Minimalism\Service\Pools\Abstracts;

use CarloNicora\Minimalism\Interfaces\LoaderInterface;

abstract class AbstractLoader
{
    /**
     * UsersLoader constructor.
     * @param LoaderInterface $loader
     */
    public function __construct(
        private LoaderInterface $loader,
    )
    {
    }

    /**
     * @return LoaderInterface
     */
    public function getLoader(): LoaderInterface
    {
        return $this->loader;
    }
}