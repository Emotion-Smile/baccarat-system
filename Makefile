ide-helper:
	php artisan clear-compiled
	php artisan ide-helper:generate
	php artisan ide-helper:meta
	php artisan ide-helper:models --nowrite

clear-cache:
	php artisan clear-compiled
	#php artisan cache:clear
	php artisan view:clear
	php artisan config:clear
	php artisan debugbar:clear
	php artisan event:clear
	php artisan optimize:clear
	php artisan route:clear

dusk:
	#php artisan migrate:fresh --seed
	php artisan pest:dusk --parallel --group=trader

deploy:
	cp .env .env.bk && rm .env
	cp .env.staging.docker .env
	vapor deploy
	rm .env && cp .env.bk .env && rm .env.bk
test:
	./vendor/bin/pest

deploy-dev:
	cp .env .env.bk && rm .env
	cp .env.production.docker .env
	vapor deploy production
	rm .env && cp .env.bk .env && rm .env.bk

php8:
	brew unlink php@8.1
	brew link --overwrite --force shivammathur/php/php@8.0
	php -v
php81:
	brew unlink php@8.0
	brew link --overwrite --force shivammathur/php/php@8.1
	php -v

dt-new:
	php artisan local:dt:create-new-game
dt-submit:
	php artisan local:dt:submit-result --dragonResult=1 --dragonType=heart --tigerResult=5 --tigerType=club
