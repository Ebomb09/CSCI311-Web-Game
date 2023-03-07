#!/bin/bash

#Install web interface stuff
echo "Copying web project files"

for file in ~/public_html/*; do
	rm -rf $file
done

cp -rp www/* ~/public_html

#Install MySQL tables
#TODO Script to load login and load tables, use non-git tracked for security
#TODO Options to load test data
echo "Loading MySQL tables"

#Done
echo 'Done installing'
