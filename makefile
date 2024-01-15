.PHONY: composer npm run-dev stop test

.DEFAULT_GOAL := help

help:
	@test -f /usr/bin/xmlstarlet || echo "Needs: sudo apt-get install --yes xmlstarlet"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# If the first argument is one of the supported commands...
SUPPORTED_COMMANDS := composer npm
SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
    # use the rest as arguments for the command
    COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    # ...and turn them into do-nothing targets
    $(eval $(COMMAND_ARGS):;@:)
endif

stop: ## stop docker container
	docker stop bibcnrs_wordpress_1
	docker stop bibcnrs_db_1

composer: ## allow to run dockerized composer command
	docker compose run --rm composer $(COMMAND_ARGS)

install: ## install dependency
	docker compose run --rm composer update

npm: ## allow to run dockerized npm command
	docker compose run --rm npm $(COMMAND_ARGS)

run-dev: ## run docker for dev enviroinment
	docker compose up --force-recreate

test: install test2

test2: ## run test
	docker compose run --rm phpunit test
