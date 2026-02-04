#! /bin/bash
#cron
rm /home/"$1"/_sql/"$1".dump
rm /home/"$1"/_sql/"$1".dump.gz
mysqldump -u root -p"$2" -h localhost --opt "$1" > /home/"$1"/_sql/"$1".dump
gzip /home/"$1"/_sql/"$1".dump
