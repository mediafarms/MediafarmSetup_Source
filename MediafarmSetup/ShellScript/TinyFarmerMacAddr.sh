#!/bin/sh
################################################################################
# Desc   : It refers to the MAC address, to be recorded in the file.
# Usage  : sh TinyFarmerMacAddr.sh   
################################################################################

FILE_NAME=mac_addr.txt
FILE_PATH=/home/ubuntu/Mediafarm/config
FILE_HOME=/home/ubuntu/Mediafarm/config/$FILE_NAME

if [ -e $FILE_HOME ]
then
  rm -rf $FILE_HOME
fi

 echo `ifconfig eth0 | grep -o -E '([[:xdigit:]]{1,2}:){5}[[:xdigit:]]{1,2}'` >> $FILE_HOME