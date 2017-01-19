#!/bin/bash

remoteName=tuna-$1
remoteUrl=git@bitbucket.org:thecodeine/tuna-$1bundle.git

if ! git config remote.tuna-$1.url; then
    git remote add $remoteName $remoteUrl;
fi
