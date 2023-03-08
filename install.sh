#!/bin/bash

# Install MySQL tables
db_conf_file=conf/db.info

echo "Reading MySQL configuration $db_conf_file"
db_host=$(sed -n 1p $db_conf_file)
db_user=$(sed -n 2p $db_conf_file)
db_pass=$(sed -n 3p $db_conf_file)

echo "Connecting to $db_host as $db_user and creating database"
mysql -h$db_host -u$db_user -p$db_pass < mysql/tables.sql

#Done
echo 'Done installing'
