#!/bin/bash

lando composer install
yarn import-data
yarn confim
yarn build-theme
lando drush uli
