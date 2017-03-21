<?php

namespace TheCodeine\MenuBundle\Twig;

use Symfony\Component\Routing\RouterInterface;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\MenuBundle\Service\MenuManager;

class MenuExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var MenuManager
     */
    protected $menuManager;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $defaultTemplate;

    /**
     * MenuExtension constructor.
     */
    public function __construct(\Twig_Environment $twig, MenuManager $menuManager, RouterInterface $router, $defaultTemplate)
    {
        $this->twig = $twig;
        $this->menuManager = $menuManager;
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
        if (!array_key_exists('root', $options)) {
            $rootFromLabel = $this->menuManager->findMenuItemByLabel($menuName);

            if (!$rootFromLabel) {
                return '';
            }
        }

        $options += [
            'wrap' => true,
            'template' => $this->defaultTemplate,
            'root' => isset($rootFromLabel) ? $rootFromLabel : $options['root'],
            'locale' => null,
        ];

        return $this->twig->render(
            $options['template'], [
                'menu' => $this->menuManager->getMenuTree($options['root']),
                'name' => $menuName,
                'options' => $options,
            ]
        );
    }
}
