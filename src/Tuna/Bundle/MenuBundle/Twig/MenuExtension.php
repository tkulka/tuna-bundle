<?php

namespace TunaCMS\Bundle\MenuBundle\Twig;

use Symfony\Component\Routing\RouterInterface;
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
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $defaultTemplate;

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
     * @param MenuInterface $menu
     *
     * @return string
     */
    public function getLink(MenuInterface $menu)
    {
        if ($menu->isUrlLinkType()) {
            return $menu->getUrl();
        }

        return $this->router->generate('tuna_menu_item', [
            'slug' => $menu->getNode()->getSlug(),
        ]);
    }

    public function renderMenu($menuName = 'Menu', array $options = [])
    {
        if (!array_key_exists('root', $options)) {
            $rootFromName = $this->menuManager->findMenuItemByName($menuName);

            if (!$rootFromName) {
                return '';
            }
        }

        $options += [
            'wrap' => true,
            'template' => $this->defaultTemplate,
            'root' => isset($rootFromName) ? $rootFromName : $options['root'],
            'locale' => null,
        ];

        return $this->twig->render(
            $options['template'],
            [
                'menu' => $this->menuManager->getMenuTree($options['root'], $options['locale']),
                'name' => $menuName,
                'options' => $options,
            ]
        );
    }
}
