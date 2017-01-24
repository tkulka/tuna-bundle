## Frontend translations:
Translations are enabled by default. You can turn them off by setting:

    the_codeine_admin:
        components:
            translations:
                enabled: false

### Import translations to database
Run this command:

    php app/console lexik:translations:import -c -g -m

### Export translations from database to yml files
If you want to dump translations from database to yml files run this command:

    php app/console lexik:translations:export

## Admin JS translations:
JavaScript translations are in `Resources/translations/tuna_admin.pl.yml`. To cache translations, run this command:

    php app/console bazinga:js-translation:dump