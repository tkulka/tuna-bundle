#!/bin/bash

remoteName=tuna-$1
splitBranch=split-$1

bundleName="$(tr '[:lower:]' '[:upper:]' <<< ${1:0:1})${1:1}Bundle"
currentBranch=${2:-`git rev-parse --abbrev-ref HEAD`}

bash bin/add_bundle_remote.sh $1

git subtree split --rejoin --prefix=src/Tuna/Bundle/$bundleName -b $splitBranch
git push --force $remoteName $splitBranch:$currentBranch
