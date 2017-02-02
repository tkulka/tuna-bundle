<?php

namespace TheCodeine\MenuBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;
use TheCodeine\MenuBundle\Entity\Menu;

class MenuExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var String
     */
    private $defaultTemplate;

    /**
     * MenuExtension constructor.
     */
    public function __construct(\Twig_Environment $twig, EntityManager $em, RouterInterface $router, $defaultTemplate)
    {
        $this->twig = $twig;
        $this->em = $em;
        $this->router = $router;
        $this->defaultTemplate = $defaultTemplate;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('tuna_menu_render', [$this, 'renderMenu'], ['is_safe' => ['html' => true]]),
            new \Twig_SimpleFunction('tuna_menu_getLink', [$this, 'getLink']),
        ];
    }

    /**
     * @param Menu|array $menu Menu object or array containing 'externalUrl', 'slug', keys
     * @return string
     */
    public function getLink(Menu $menu)
    {
        if ($menu->getExternalUrl()) {
            return $menu->getExternalUrl();
        } else {
            return $this->router->generate('tuna_menu_item', ['slug' => $menu->getSlug()]);
        }
    }

    public function renderMenu($menuName = 'Menu', array $options = [])
    {
        $options += ['wrap' => true, 'template' => $this->defaultTemplate];

        return $this->twig->render(
            $options['template'], [
                'menu' => $this->em->getRepository(Menu::class)->getMenuTree($menuName),
                'name' => $menuName,
                'options' => $options,
            ]
        );
    }
}
