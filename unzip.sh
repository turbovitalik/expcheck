#!/bin/sh

DIR=/var/www/expcheck
UPLOADS_DIR=$DIR/storage/app/uploads

for file in $UPLOADS_DIR/pool.zip
do
    POOL_DIR_NAME="`date +%m`_`date +%d`_`date +%y`-pool"
    unzip -n "$file" -d $UPLOADS_DIR/tmp &&
    mkdir $UPLOADS_DIR/$POOL_DIR_NAME &&
    mv $UPLOADS_DIR/tmp/* $UPLOADS_DIR/$POOL_DIR_NAME/list.txt
    rm -irf $UPLOADS_DIR/tmp
done