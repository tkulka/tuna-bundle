<?php

namespace TunaCMS\Bundle\MenuBundle\Twig;

use Symfony\Component\Routing\RouterInterface;
use TunaCMS\Bundle\MenuBundle\Factory\MenuFactory;
use TunaCMS\Bundle\MenuBundle\Model\ExternalUrlInterface;
use TunaCMS\Bundle\MenuBundle\Model\MenuAliasInterface;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\MenuBundle\Service\MenuManager;

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
     * @var MenuFactory
     */
    protected $menuFactory;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $templates;

    public function __construct(\Twig_Environment $twig, MenuManager $menuManager, MenuFactory $menuFactory, RouterInterface $router, $templates)
    {
        $this->twig = $twig;
        $this->menuManager = $menuManager;
        $this->menuFactory = $menuFactory;
        $this->router = $router;
        $this->templates = $templates;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('tuna_menu_render', [$this, 'renderMenu'], [
                'is_safe' => [
                    'html' => true,
                ],
            ]),
            new \Twig_SimpleFunction('tuna_menu_getLink', [$this, 'getLink']),
            new \Twig_SimpleFunction('resolve_menu_type', [$this, 'resolveMenuType']),
        ];
    }

    /**
     * @param MenuInterface $menu
     *
     * @return string
     */
    public function getLink(MenuInterface $menu)
    {
        if ($menu instanceof MenuAliasInterface) {
            $menu = $menu->getTargetMenu();

            if (!$menu) {
                return null;
            }
        }

        if ($menu instanceof ExternalUrlInterface) {
            return $menu->getUrl();
        }

        return $this->router->generate('tuna_menu_item', [
            'slug' => $menu->getSlug(),
        ]);
    }

    public function renderMenu($menuName = 'Menu', array $options = [], array $templates = [])
    {
        if (!array_key_exists('root', $options)) {
            $rootFromName = $this->menuManager->findMenuItemByName($menuName);

            if (!$rootFromName) {
                return '';
            }
        }

        $options += [
            'wrap' => true,
            'root' => isset($rootFromName) ? $rootFromName : $options['root'],
        ];

        $templates += $this->templates;

        return $this->twig->render(
            $templates['menu'],
            [
                'menu' => $this->menuManager->getMenuTree($options['root']),
                'name' => $menuName,
                'templates' => $templates,
                'options' => $options,
            ]
        );
    }

    public function resolveMenuType(MenuInterface $menu)
    {
        return $this->menuFactory->getTypeName($menu);
    }
}
