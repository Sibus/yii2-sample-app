.PHONY: run composer/install test yii/migrate/up yii_test/migrate/up up stop down destroy ps
.DEFAULT_GOAL := run

define do_exec
	docker-compose exec -it ${1}
endef

run: memory .env up composer/install yii/migrate/up

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

memory:
	@if [ "$$(sysctl -n vm.max_map_count)" -lt 262144 ]; then \
		echo "The vm.max_map_count kernel setting must be set to at least 262144"; \
		echo "Run"; \
		echo "sudo sysctl -w vm.max_map_count=262144;"; \
		exit 1; \
	fi
