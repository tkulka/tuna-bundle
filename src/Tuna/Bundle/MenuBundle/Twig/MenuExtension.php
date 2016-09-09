<?php

namespace TheCodeine\MenuBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
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
     * @var Router
     */
    private $router;

    /**
     * MenuExtension constructor.
     */
    public function __construct(\Twig_Environment $twig, EntityManager $em, Router $router)
    {
        $this->twig = $twig;
        $this->em = $em;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'tuna_menu_render',
                array($this, 'renderMenu'),
                array(
                    'is_safe' => array('html' => true)
                )
            ),
            new \Twig_SimpleFunction(
                'tuna_menu_getLink',
                array($this, 'getLink')
            ),
        );
    }

    public function getLink($menu)
    {
        if ($menu['externalUrl']) {
            return $menu['externalUrl'];
        } else {
            return $this->router->generate('tuna_menu_item', array('slug' => $menu['slug']));
        }
    }

    public function renderMenu($menuName = 'Menu', array $options = array())
    {
        $repository = $this->em->getRepository('TheCodeineMenuBundle:Menu');
        $root = $repository->findOneBy(array(
            'label' => $menuName,
            'lvl' => 0,
        ));
        if (!$root) {
            throw new \Exception('There\'s no menu like this');
        }

        $nodes = $repository->getNodesHierarchy($root);
        $tree = $repository->buildTree($nodes);

        if (!key_exists('wrap', $options)) {
            $options['wrap'] = true;
        }

        return $this->twig->render(
            'TheCodeineMenuBundle:Menu:render_menu.html.twig',
            array(
                'menu' => $tree,
                'name' => $menuName,
                'options' => $options,
            )
        );
    }

    public function getName()
    {
        return 'tuna_menu_extension';
    }
}
