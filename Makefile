install:
	composer install

test:
	./vendor/bin/phpunit

cs-fixer:
	./vendor/bin/php-cs-fixer fix src

stan:
	./vendor/bin/phpstan


