#!/bin/bash
nohup bash -c "php Behat/Fixtures/app/console --env=test --no-debug doctrine:schema:drop --force 2>&1 &" && sleep 1;
nohup bash -c "php Behat/Fixtures/app/console --env=test --no-debug doctrine:schema:create 2>&1 &" && sleep 1;
nohup bash -c "php Behat/Fixtures/app/console --env=test --no-debug doctrine:fixtures:load --append 2>&1 &" && sleep 1;
nohup bash -c "php Behat/Fixtures/app/console --env=test --no-debug cache:clear 2>&1 &" && sleep 1;
nohup bash -c "php Behat/Fixtures/app/console --env=test --no-debug server:run 2>&1 &" && sleep 1;
vendor/bin/behat -f progress -n;
vendor/bin/phpspec run --format=pretty --no-code-generation;
kill $(lsof -i :8000 | grep php | awk '{print $2}')