#!/bin/sh

DIR=/var/www/expcheck
UPLOADS_DIR=$DIR/storage/app/uploads
POOLS_UNZIP_DIR=$DIR/storage/app/pool_downloads

for file in $UPLOADS_DIR/pool.zip
do
    POOL_DIR_NAME="`date +%m`_`date +%d`_`date +%y`-pool"
    unzip -n "$file" -d $UPLOADS_DIR/tmp &&
    mkdir $POOLS_UNZIP_DIR/$POOL_DIR_NAME &&
    mv $UPLOADS_DIR/tmp/* $POOLS_UNZIP_DIR/$POOL_DIR_NAME/list.txt
    rm -irf $UPLOADS_DIR/tmp
done