<?php

namespace TheCodeine\MenuBundle\Twig;

use Doctrine\ORM\EntityManager;

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
     * MenuExtension constructor.
     */
    public function __construct(\Twig_Environment $twig, EntityManager $em)
    {
        $this->twig = $twig;
        $this->em = $em;
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
        );
    }

    public function renderMenu($menuName = 'Menu')
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

        return $this->twig->render(
            'TheCodeineMenuBundle:Menu:render_menu.html.twig',
            array(
                'menu' => $tree,
                'name' => $menuName,
            )
        );
    }

    public function getName()
    {
        return 'tuna_menu_extension';
    }
}
