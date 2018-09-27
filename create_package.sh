#!/usr/bin/env bash

composer install

rm -rv build/*

mkdir build/ShopwareBlogBugsnag

cp -rv Resources Source vendor LICENSE README.md ShopwareBlogBugsnag.php build/ShopwareBlogBugsnag/

cd ./build && zip -r ./ShopwareBlogBugsnag.zip ./ShopwareBlogBugsnag
