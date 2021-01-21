<?php
namespace CarloNicora\Minimalism\Services\Pools\Abstracts;

use CarloNicora\Minimalism\Interfaces\DataInterface;
use CarloNicora\Minimalism\Interfaces\DataLoaderInterface;
use CarloNicora\Minimalism\Interfaces\LoaderInterface;

abstract class AbstractDataLoader extends AbstractLoader implements DataLoaderInterface
{
    /**
     * UsersLoader constructor.
     * @param LoaderInterface $loader
     * @param DataInterface $data
     */
    public function __construct(
        LoaderInterface $loader,
        protected DataInterface $data,
    )
    {
        parent::__construct($loader);
    }
}