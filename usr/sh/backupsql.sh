#! /bin/bash
#psw=
mysqldump -u root -p -h localhost ffw > /home/ffw/_sql/ffw.dump
gzip /home/ffw/_sql/ffw.dump
