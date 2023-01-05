.PHONY: run composer/install test yii/migrate/up yii_test/migrate/up up stop down destroy ps
.DEFAULT_GOAL := run

define do_exec
	docker-compose exec -it ${1}
endef

run: .env up composer/install yii/migrate/up

composer/install:
	$(call do_exec, php composer install)

yii/migrate/up:
	$(call do_exec, php php yii migrate/up --interactive=0)

yii_test/migrate/up:
	$(call do_exec, php php yii_test migrate/up --interactive=0)

test: yii_test/migrate/up
	$(call do_exec, php vendor/bin/codecept run)

.env:
	cp .env.example $@

########################################################################################################################

up:
	docker-compose up --remove-orphans --detach

stop:
	docker-compose stop

down:
	docker-compose down --remove-orphans

destroy:
	docker-compose down --remove-orphans -v

ps:
	docker-compose ps
