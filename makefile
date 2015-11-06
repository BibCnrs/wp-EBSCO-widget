.PHONY: composer npm run-dev stop

# If the first argument is one of the supported commands...
SUPPORTED_COMMANDS := composer npm
SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
    # use the rest as arguments for the command
    COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    # ...and turn them into do-nothing targets
    $(eval $(COMMAND_ARGS):;@:)
endif

stop:
	docker stop bibcnrs_wordpress_1
	docker stop bibcnrs_db_1

composer:
	docker-compose run composer $(COMMAND_ARGS)

npm:
	docker-compose run npm $(COMMAND_ARGS)

run-dev:
	docker-compose up
