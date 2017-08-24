## Frontend translations:
Translations are enabled by default. You can turn them off by setting:

    tuna_cms_admin:
        components:
            translations:
                enabled: false
                
### Extract translations from 
After adding new translation keys developer should extract them into yml files (also can provide default values):

    php app/console tuna:translations:extract

By default it will look for translations in `app/Resources` and generate files in `app/Resources/translations`.  
If you want to extract translations only in given bundle provide `bundle` argument:

    php app/console tuna:translations:extract FrontendBundle # bundle name
    php app/console tuna:translations:extract src/FrontendBundle # dir path

### Update DB translations (available in /admin/translations)
Run this command:

    php app/console tuna:translations:update
    
By default it will look for translation files in `app/Resources/translations`.    
If you want to update translations only in given bundle provide `bundle` argument:

    php app/console tuna:translations:update FrontendBundle # bundle name

### Delete DB translations
Be careful with this command because it deletes all translations and cannot be undone (you can only regenerate DB translation with default values from yml files).

    php app/console tuna:translations:delete
    
## Admin JS translations:
JavaScript translations are in `Resources/translations/tuna_admin.pl.yml`. To cache translations, run this command:

    php app/console bazinga:js-translation:dump
