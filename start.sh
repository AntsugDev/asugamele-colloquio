#!/bin/bash
echo "start migrate"

php artisan migrate:refresh  --seed

echo "migrate terminate with success!!!"

echo "start schedule"

php artisan schedule:work
