php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:migrations:migrate
php bin/console --env=test doctrine:fixtures:load