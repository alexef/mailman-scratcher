#!/bin/bash

if [ "$1" == "" ]; then
	echo "Usage: $0 <list-name>"
	exit -1
fi
if [ "$2" == "--local" ]; then
	HOST="localhost/list/"
else
	HOST=""
fi

LIST=$1
PIPERMAIL_URL='http://'$HOST'doctorat.sas.unibuc.ro/pipermail/'$LIST'/'
ARTICLES_LIST=$LIST/articles.txt
ARTICLES_DIR=$LIST/articles/

# Get months list
MONTHS=$(wget $PIPERMAIL_URL -q -O - | grep date.html | cut -f2 -d\")

touch $ARTICLES_LIST
cp $ARTICLES_LIST $ARTICLES_LIST-old
> $ARTICLES_LIST
mkdir -p $ARTICLES_DIR
 
for m in $MONTHS ; do
	MONTH=$(echo $m | cut -d/ -f1)
	wget $PIPERMAIL_URL""$m -q -O -  | tac | grep \<LI  | cut -f2,3 -d\" | sed 's/">/$/g' | sed "s/^/$MONTH$/g" >> $ARTICLES_LIST 
done

# real new lines
diff $ARTICLES_LIST $ARTICLES_LIST-old | grep ">" | cut -d\  -f2- > $ARTICLES_LIST-current

IFS=$'\n'
for a in $(cat $ARTICLES_LIST-current); do
	URI=$(echo $a | sed 's/\$/\//g' | cut -f1,2 -d/)
	ID=$(echo $a | cut -f2 -d$ | cut -f1 -d.)
	#wget $PIPERMAIL_URL""$URI -q -O - | grep beginarticle -A1000 | grep endarticle -B1000 | tail -n +2 | head -n -1 > $ARTICLES_DIR/$ID.html
	wget $PIPERMAIL_URL""$URI -q -O $ARTICLES_DIR/$ID.html
done
