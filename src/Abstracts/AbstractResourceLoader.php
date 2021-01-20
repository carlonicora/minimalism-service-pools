<?php
namespace CarloNicora\Minimalism\Service\Pools\Abstracts;

use CarloNicora\Minimalism\Interfaces\ServiceInterface;
use CarloNicora\Minimalism\Interfaces\LoaderInterface;
use CarloNicora\Minimalism\Services\JsonApi\JsonApi;

abstract class AbstractResourceLoader extends AbstractLoader
{
    /**
     * UsersLoader constructor.
     * @param LoaderInterface $loader
     * @param ServiceInterface|JsonApi $jsonApi
     */
    public function __construct(
        LoaderInterface $loader,
        protected ServiceInterface|JsonApi $jsonApi,
    )
    {
        parent::__construct($loader);
    }
}