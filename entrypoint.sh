#!/bin/sh

rm /var/www/localhost/htdocs/index.html

chown -R www-data:www-data /var/www/

httpd -k stop

rc-status

/etc/init.d/mariadb setup

/etc/init.d/mariadb start

# Create 'mydatabase' database and 'users' table
mysql -uroot -e "CREATE DATABASE db;"
mysql -uroot -e "CREATE TABLE db.users (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, email VARCHAR(30) NOT NULL, password VARCHAR(30) NOT NULL, role VARCHAR(30) NOT NULL);"

# Create new MySQL user and grant permissions to 'mydatabase'
mysql -uroot -e "CREATE USER 'app'@'localhost' IDENTIFIED BY 'l5FP^6g3an9s';"
mysql -uroot -e "GRANT ALL PRIVILEGES ON db.* TO 'app'@'localhost';"

# Generate a random password
PASSWORD=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 16 | head -n 1)
mysql -uroot -e "INSERT INTO db.users (email, password, role) VALUES ('admin@test.com', '$PASSWORD', 'admin');"

# Start apache
rc-service apache2 start

# Cheat
tail -f /var/log/apache2/access.log
