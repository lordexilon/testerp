#!/bin/sh
echo $LD_LIBRARY_PATH | egrep "/home/villalbamedina/dolibarr-3.9.1-1/common" > /dev/null
if [ $? -ne 0 ] ; then
PATH="/home/villalbamedina/dolibarr-3.9.1-1/sqlite/bin:/home/villalbamedina/dolibarr-3.9.1-1/php/bin:/home/villalbamedina/dolibarr-3.9.1-1/mysql/bin:/home/villalbamedina/dolibarr-3.9.1-1/apache2/bin:/home/villalbamedina/dolibarr-3.9.1-1/common/bin:$PATH"
export PATH
LD_LIBRARY_PATH="/home/villalbamedina/dolibarr-3.9.1-1/sqlite/lib:/home/villalbamedina/dolibarr-3.9.1-1/mysql/lib:/home/villalbamedina/dolibarr-3.9.1-1/apache2/lib:/home/villalbamedina/dolibarr-3.9.1-1/common/lib:$LD_LIBRARY_PATH"
export LD_LIBRARY_PATH
fi

TERMINFO=/home/villalbamedina/dolibarr-3.9.1-1/common/share/terminfo
export TERMINFO
##### SQLITE ENV #####
			
SASL_CONF_PATH=/home/villalbamedina/dolibarr-3.9.1-1/common/etc
export SASL_CONF_PATH
SASL_PATH=/home/villalbamedina/dolibarr-3.9.1-1/common/lib/sasl2 
export SASL_PATH
LDAPCONF=/home/villalbamedina/dolibarr-3.9.1-1/common/etc/openldap/ldap.conf
export LDAPCONF
##### PHP ENV #####
PHP_PATH=/home/villalbamedina/dolibarr-3.9.1-1/php/bin/php
export PHP_PATH
##### MYSQL ENV #####

##### APACHE ENV #####

##### CURL ENV #####
CURL_CA_BUNDLE=/home/villalbamedina/dolibarr-3.9.1-1/common/openssl/certs/curl-ca-bundle.crt
export CURL_CA_BUNDLE
##### SSL ENV #####
SSL_CERT_FILE=/home/villalbamedina/dolibarr-3.9.1-1/common/openssl/certs/curl-ca-bundle.crt
export SSL_CERT_FILE
OPENSSL_CONF=/home/villalbamedina/dolibarr-3.9.1-1/common/openssl/openssl.cnf
export OPENSSL_CONF
OPENSSL_ENGINES=/home/villalbamedina/dolibarr-3.9.1-1/common/lib/engines
export OPENSSL_ENGINES


. /home/villalbamedina/dolibarr-3.9.1-1/scripts/build-setenv.sh
