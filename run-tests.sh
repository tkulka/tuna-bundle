#!/usr/bin/env bash

exitCode=0;

# Test commands
commands=(
    "vendor/bin/phpspec run"
    "vendor/bin/phpunit"
)

# Loop through all test commands
for i in "${commands[@]}"
do
   ${i}

   # If test command failed set exitCode to 1 (the CI should throw an error)
   if [ $? -ne 0 ]; then exitCode=1; fi
done

# Exit with exitCode (0|1)
exit ${exitCode}