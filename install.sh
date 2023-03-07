#!/bin/bash

#Install web interface stuff
for file in ~/public_html/*; do
	rm -rf $file
done
cp -rp www/* ~/public_html
