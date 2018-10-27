# Steps to deploy

1. docker-compose up -d
2. composer install
3. Import est.sql database

# Phpunit
./vendor/bin/phpunit --stderr --bootstrap vendor/autoload.php tests/WorkTest