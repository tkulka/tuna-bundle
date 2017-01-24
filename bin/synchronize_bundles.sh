#!/bin/bash

bundles=(
    category
    editor
    file
    gallery
    image
    menu
    news
    page
    tag
    translation
    user
    video
)

for bundle in "${bundles[@]}"
do
    bash bin/push_subtree.sh $bundle $1
done