#!/usr/bin/env bash

# install dependencies
composer install

# clear and warmup app cache
app/console cache:clear --env=prod
app/console cache:warmup --env=prod

# initialize the database
app/console broadway:event-store:schema:init --env=prod

# run the app
app/console server:run --env=prod -vvv 0.0.0.0:8000
