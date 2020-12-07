#!/bin/bash
TAG=$1

# Get new tags from the remote
sudo git fetch --tags

if [  "$TAG" == "0" ]; then
  TAG=$(git tag --sort=-version:refname | head -n 8 | sed -n '1p');
elif [ "$TAG" == "1" ]; then
  TAG=$(git tag --sort=-version:refname | head -n 8 | sed -n '2p');
elif [ "$TAG" == "2" ]; then
  TAG=$(git tag --sort=-version:refname | head -n 8 | sed -n '3p');
elif [ "$TAG" == "3" ]; then
  TAG=$(git tag --sort=-version:refname | head -n 8 | sed -n '4p');
elif [ "$TAG" == "4" ]; then
  TAG=$(git tag --sort=-version:refname | head -n 8 | sed -n '5p');
fi

sudo git checkout tags/$TAG
