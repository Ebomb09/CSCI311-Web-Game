#!/bin/bash

# Create Include for PHP Home Directory
home=$PWD
project_include=www/include/project.php

echo "Generating $project_include"
echo "<?php \$project_home=\"$home\"; ?>" > $project_include

# Install MySQL tables
db_conf_file=conf/db.info

echo "Reading MySQL configuration $db_conf_file"
db_host=$(sed -n 1p $db_conf_file)
db_user=$(sed -n 2p $db_conf_file)
db_pass=$(sed -n 3p $db_conf_file)
db_name=$(sed -n 4p $db_conf_file)

echo "Connecting to $db_host as $db_user and creating database"
query=$(cat mysql/tables.sql)
query=$(echo $query | sed "s/\${db_name}/$db_name/g")
echo "$query" | mysql -h$db_host -u$db_user -p$db_pass

# Done
echo 'Done installing'
