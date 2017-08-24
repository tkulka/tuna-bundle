## Overriding Tuna

### General
Use bundle overriding:

    use Symfony\Component\HttpKernel\Bundle\Bundle;

    class MyAdminBundle extends Bundle
    {
        public function getParent()
        {
            return 'TunaCMSAdminBundle';
        }
    }

### Menu
Add `menu_builder` class to config, and override, or use your own Builder:

    class Builder extends \TunaCMS\AdminBundle\Menu\Builder
    {
        protected function buildTopMenu(Request $request)
        {
            $menu = parent::buildTopMenu($request);
            /**
             * use custom position to inject your pages between standard links, e.g.:
             *      Pages: 100
             *      News: 110
             *      Translations: 500
             */
            $this->addChild($menu, $request, 'Projects', 'tuna_page_list', 90, function ($request, $route) {
                return preg_match_all('/app_project_/i', $request->get('_route'));
            });

            return $menu;
        }
    }

### Editor styles
To override default editor styles, add `typography.scss` and `fonts.scss` to directory specified in `tuna_cms_admin.components.editor.wysiwyg_style_dir` (remember to change it in your config). Example:

    tuna_cms_admin:
        components:
            editor:
                wysiwyg_style_dir: "%kernel.root_dir%/../src/AppBundle/Resources/public/Frontend/sass/base/"
