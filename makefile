up:
	@docker-compose -f docker-compose.yml up -d
	@docker-compose exec php /bin/sh -c "composer install"
	@docker-compose exec php /bin/sh -c "php bin/console doctrine:migrations:migrate"
	@docker-compose exec php /bin/sh -c "php bin/console doctrine:fixtures:load"
	@docker-compose exec php /bin/sh -c "php bin/console messenger:consume async -vv"