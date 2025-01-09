### Installation Guide###
composer install
sudo apt-get -y install gcc make autoconf libc-dev pkg-config
sudo pecl8.3-sp install apcu
php -v
sudo pecl8.4.2-sp install apcu
sudo apt install php-pear
sudo pecl install apcu
whereis php
php --ini
sudo pecl install apcu
pecl list
sudo nano /etc/php/8.4/cli/php.ini
sudo pecl install apcu
sudo nano /etc/php/8.4/cli/php.ini

leaf serve

### PHP INI CONF ###
extension=apcu.so;

[apc]  
apc.enabled=1
apc.shm_size=128M
apc.ttl=7200
apc.user_ttl=7200
apc.gc_ttl=3600
apc.max_file_size=1M
