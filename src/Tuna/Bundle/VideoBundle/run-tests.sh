#!/bin/bash
exit_code=0;
commands=(
    "vendor/bin/phpspec run --format=pretty --no-code-generation"
)

for i in "${commands[@]}"
do
   $i
   if [ $? -ne 0 ]; then
      exit_code=1
   fi
done

exit $exit_code