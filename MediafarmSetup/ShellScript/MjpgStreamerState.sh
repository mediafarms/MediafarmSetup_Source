#!/bin/sh
################################################################################
# Desc   : mjpg-streamer process id check
# Usage  : sh MjpgStreamerStats.sh 0[1]
################################################################################

MJPG_HOME=/home/ubuntu/mjpg-streamer/mjpg-streamer
MJPG_NAME=mjpg_streamer


if [ "$1" != "" ]
then
    if [ $1 -eq 0 -o $1 -eq 1 ]
    then
        echo `ps -ef|grep ${MJPG_HOME}|grep ${MJPG_NAME}|grep video"$1"|awk '{print $2}'`
    else
        echo "Usage : sh MjpgStreamerStats.sh 0[1]"
    fi
else
    echo "Usage : sh MjpgStreamerStats.sh 0[1]"
fi