#!/usr/bin/env bash

# install dependencies
composer install --ignore-platform-reqs # ignore required mongo PHP extension as it is not used in this application

# initialize the database
app/console broadway:event-store:schema:init

# run the app
app/console server:run -vvv --env=prod 0.0.0.0:8000
