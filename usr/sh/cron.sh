#! /bin/bash
#cron
if [ -f /home/"$1"/_sql/"$1".dump ]; then
	rm /home/"$1"/_sql/"$1".dump
fi
if [ -f /home/"$1"/_sql/"$1".dump.gz ]; then
	rm /home/"$1"/_sql/"$1".dump.gz
fi

mysqldump -u root -p"$2" -h localhost --opt "$1" > /home/"$1"/_sql/"$1".dump
gzip /home/"$1"/_sql/"$1".dump
