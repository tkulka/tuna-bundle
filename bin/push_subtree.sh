#!/bin/bash

splitBranch=split-$1
remoteName=tuna-$1
bundleName="$(tr '[:lower:]' '[:upper:]' <<< ${1:0:1})${1:1}Bundle"
currentBranch=${2:-`git rev-parse --abbrev-ref HEAD`}

bash bin/add_bundle_remote.sh $1
echo git subtree split --prefix=src/Tuna/Bundle/$bundleName -b $splitBranch
echo git push --force $remoteName $splitBranch:$currentBranch
