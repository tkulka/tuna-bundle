## Menu
1. Add root menu item to migration/fixture:

        $this->addSql('INSERT INTO menu (id, root_id, label, clickable, publish_date, lft, rgt) VALUES(1, 1, "Menu", 1, "2016-09-12 13:00:00", 1, 2)');

2. Add action to your frontend controller (remember to add this route at the end):

        /**
         * @Route("/{slug}", requirements={"slug"=".+"}, name="tuna_menu_item")
         * @Template()
         */
        public function pageAction(Menu $menu)
        {
            if (!$menu->isPublished() || !$menu->getPage()) {
                throw new NotFoundHttpException();
            }

            return array(
                'page' => $menu->getPage(),
            );
        }

3. Render your menu in template:

        {{ tuna_menu_render() }}

You can also use full syntax:

    {{ tuna_menu_render('Label', {
        template: 'default/partials/render_menu.html.twig',
        wrap: false,
        root: null
    }) }}

* `Label` - label of the root menu item (defaults to `Menu`)
* `template` - custom menu template (defaults to `'TunaCMSMenuBundle:Menu:render_menu.html.twig'`). You can also set default template in config.
* `wrap` - whether wrap menu in `<ul>` tags or not (defaults to true)
* `root` - menu root element (defaults to Menu item found by `Label`)
