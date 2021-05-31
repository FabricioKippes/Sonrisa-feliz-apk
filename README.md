# Sonrisa Feliz API

## Local Setup

1. Clone the repository using `git clone git@gitlab.com:msi-sonrisa-feliz/sonrisa-feliz-api.git`
2. Install dependencies `lando composer install`
3. Start the application `lando start`
4. Run migrations `lando artisan:migrate`
5. Enjoy Laravel `https://sonrisa-feliz-api.lndo.site/`

## Tests

To validate that everything works run the tests

` lando php-unit`

## Generate API Doc

`lando artisan l5-swagger:generate`
