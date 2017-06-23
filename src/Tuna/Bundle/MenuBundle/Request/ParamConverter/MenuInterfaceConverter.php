<?php

namespace TheCodeine\MenuBundle\Request\ParamConverter;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Symfony\Component\HttpFoundation\Request;
use TheCodeine\MenuBundle\Entity\MenuInterface;
use TheCodeine\MenuBundle\Service\MenuManager;

class MenuInterfaceConverter extends DoctrineParamConverter
{
    /**
     * @var MenuManager
     */
    protected $menuManager;

    public function __construct(ManagerRegistry $registry = null, MenuManager $menuManager)
    {
        parent::__construct($registry);
        $this->menuManager = $menuManager;
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
        if ($configuration->getClass() === MenuInterface::class) {
            $configuration->setClass($this->menuManager->getClassName());
        }
    }
}
