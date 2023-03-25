#how to install and start project
1) go to project root and run this command
```shell
composer install
```
2) after project dependencies installation run 
this command 
```shell
docker-compose up -d
```
3)then open php container and run this command from container
```shell
docker-compose exec php 
```
#Run this command from `php` container for messages
```shell
php bin/console messenger:consume async -vv
```
