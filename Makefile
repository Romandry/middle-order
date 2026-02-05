PHP = /usr/local/opt/php@8.3/bin/php
PHP_NO_XDEBUG = XDEBUG_MODE=off $(PHP)

CS_CONFIG = .php-cs-fixer.php

cache-clear:
	$(PHP_NO_XDEBUG) bin/console cache:clear

cache-warmup:
	$(PHP_NO_XDEBUG) bin/console cache:warmup

test:
	$(PHP_NO_XDEBUG) vendor/bin/phpunit

cs-fix:
	$(PHP_NO_XDEBUG) vendor/bin/php-cs-fixer fix --config=$(CS_CONFIG)

cs-check:
	$(PHP_NO_XDEBUG) vendor/bin/php-cs-fixer fix --config=$(CS_CONFIG) --dry-run --diff

stan:
	$(PHP_NO_XDEBUG) -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon

check: cs-check stan test
