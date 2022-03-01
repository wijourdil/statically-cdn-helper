#!Make

# Setup project
setup:
	composer install

# Run phpcs + phpstan
test:
	./vendor/bin/phpcs
	./vendor/bin/phpstan analyse --memory-limit=2G
