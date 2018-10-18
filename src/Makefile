all: setup

setup: set_config set_db gen_json_schema


set_db:
	php artisan migrate
	php artisan db:seed

set_config:
	cp .env.local .env
	composer install

gen_json_schema:
	php artisan utils:yamltojson


check-style:
	./vendor/bin/php-cs-fixer fix --dry-run app
	./vendor/bin/php-cs-fixer fix --dry-run tests
	./vendor/bin/php-cs-fixer fix --dry-run config
	./vendor/bin/php-cs-fixer fix --dry-run routes
	./vendor/bin/php-cs-fixer fix --dry-run database

fix:
	./vendor/bin/php-cs-fixer fix app
	./vendor/bin/php-cs-fixer fix tests
	./vendor/bin/php-cs-fixer fix config
	./vendor/bin/php-cs-fixer fix routes
	./vendor/bin/php-cs-fixer fix database

test: check-style setup
	/vendor/bin/phpunit -d memory_limit=512M

