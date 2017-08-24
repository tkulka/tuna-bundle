#!/bin/bash

bundles=(
    editor
    file
)

for bundle in "${bundles[@]}"
do
    bash bin/push_subtree.sh $bundle $1
done
