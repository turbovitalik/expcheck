#!/bin/sh

DIR=/var/www/expcheck

for file in $DIR/pool.zip
do
    POOL_DIR_NAME="`date +%m`_`date +%d`_`date +%y`-pool"
    unzip -n "$file" -d $DIR/tmp &&
    mkdir $DIR/$POOL_DIR_NAME &&
    mv $DIR/tmp/* $DIR/$POOL_DIR_NAME/list.txt
    rm -irf $DIR/tmp
done