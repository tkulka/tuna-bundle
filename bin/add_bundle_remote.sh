#!/bin/bash

remoteName=tuna-$1

if ! git config remote.$remoteName.url; then
    git remote add $remoteName git@github.com:tuna-cms/$1.git;
fi