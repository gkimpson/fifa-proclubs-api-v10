#!/bin/bash
echo 'Run PHP CodeSniffer';
./vendor/bin/phpcs -v app
echo 'Run Laravel Pint';
./vendor/bin/pint
#echo 'Run artisan test'
#php artisan test
echo 'PHPStan'
./vendor/bin/phpstan analyse
