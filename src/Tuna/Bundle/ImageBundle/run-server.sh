#!/bin/bash
php Behat/Fixtures/app/console --env=test --no-debug doctrine:schema:drop --force
php Behat/Fixtures/app/console --env=test --no-debug doctrine:schema:create
php Behat/Fixtures/app/console --env=test --no-debug cache:clear
php Behat/Fixtures/app/console --env=test --no-debug server:run