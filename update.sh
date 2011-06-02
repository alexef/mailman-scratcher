#!/bin/bash

LISTS="doctorat-anunturi doctorat-interdisc doctorat-avizier doctorat-postdoc"

cd /opt/admin/mailman-scratcher

for i in $LISTS ; do
	./scratch-all.sh $i
done

