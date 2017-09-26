<?php

namespace TunaCMS\Component\Common\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class EntityInterfaceConverter extends DoctrineParamConverter
{
    protected $interfaceClass;

    protected $entityClass;

    /**
     * @param ManagerRegistry $registry
     * @param $interfaceClass
     * @param $entityClass
     */
    public function __construct(ManagerRegistry $registry, $interfaceClass, $entityClass)
    {
        parent::__construct($registry);

        $this->interfaceClass = $interfaceClass;
        $this->entityClass = $entityClass;
    }

    public function supports(ParamConverter $configuration)
    {
        $this->mapClass($configuration);

        return parent::supports($configuration);
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $this->mapClass($configuration);

        return parent::apply($request, $configuration);
    }

    private function mapClass(ParamConverter $configuration)
    {
        if ($configuration->getClass() === $this->interfaceClass) {
            $configuration->setClass($this->entityClass);
        }
    }
}
