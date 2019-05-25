#!/bin/bash
DIR=$1

if [ ! -n "$DIR" ] ;then
    echo "you have not choice Application directory !"
    exit
fi

php easyswoole stop
php easyswoole start --d

fswatch -r $DIR | while read file
do
   echo "${file} was modify" >> ./Temp/reload.log 2>&1
   php easyswoole reload
done
