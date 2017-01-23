#!/bin/bash

bundles=(file)

for bundle in $bundles; do
    bash bin/push_subtree.sh $bundle
done