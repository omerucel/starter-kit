#!/bin/bash

export SHELL_SCRIPT_MODULE_PATH="/vagrant-modules"
source "${SHELL_SCRIPT_MODULE_PATH}/lib.sh"

runModules "base" "php5-apt-fix" "mysql" "php5" "php5-curl" "php5-gd" "php5-mcrypt" "php5-mysql" "php5-xdebug" "php5-json" "php5-fpm" "nginx"

service apache2 stop
update-rc.d apache2 disable
service php5-fpm restart
service nginx restart