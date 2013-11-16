#!/bin/bash

export SHELL_SCRIPT_MODULE_PATH="/vagrant-modules"
export NGINX_VHOST_FILE="/vagrant/vagrant/files/vhost.conf"
export XDEBUG_REMOTE_DEBUGGING=1

source "${SHELL_SCRIPT_MODULE_PATH}/lib.sh"

runModules "base" "remi" "mysql" "php54" "httpd"