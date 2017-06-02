#
# Unilead | HasOffers
#
# This file is part of the Unilead Service Package.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package      HasOffers
# @license      Proprietary
# @copyright    Copyright (C) Unilead Network, All rights reserved.
# @link         https://www.unileadnetwork.com
#

.PHONY: build update test-all validate autoload test phpmd phpcs phpcpd phploc reset

build: update

test-all:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Run all tests \033[0m"
	@make validate test phpmd phpcs phpcpd phploc

update:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Update project \033[0m"
	@composer update --optimize-autoloader --no-interaction
	@echo ""

validate:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Composer validate \033[0m"
	@composer validate --no-interaction
	@echo ""

test:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Run unit-tests \033[0m"
	@php ./vendor/phpunit/phpunit/phpunit
	@echo ""

phpmd:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Check PHPmd \033[0m"
	@php ./vendor/phpmd/phpmd/src/bin/phpmd ./src text                  \
         ./vendor/unilead/codestyle/src/phpmd/unilead.xml --verbose

phpcs:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Check Code Style \033[0m"
	@php ./vendor/squizlabs/php_codesniffer/scripts/phpcs ./src                 \
        --extensions=php                                                        \
        --standard=./vendor/unilead/codestyle/src/phpcs/Unilead/ruleset.xml     \
        --report=full
	@echo ""

phpcpd:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Check Copy&Paste \033[0m"
	@php ./vendor/sebastian/phpcpd/phpcpd ./src --verbose
	@echo ""

phploc:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Show stats \033[0m"
	@php ./vendor/phploc/phploc/phploc ./src --verbose
	@echo ""

reset:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Hard reset \033[0m"
	@git reset --hard

clean:
	@echo "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Cleanup project \033[0m"
	@rm -rf ./vendor/
	@rm -f ./composer.lock
