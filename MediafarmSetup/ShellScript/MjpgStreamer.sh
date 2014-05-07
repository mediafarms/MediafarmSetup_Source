################################################################################
# Desc   : mjpg-streamer start 
# Usage  : sh MjpgStreamer.sh 0[1]
################################################################################

MJPG_HOME=/home/ubuntu/mjpg-streamer/mjpg-streamer
MJPG_NAME=mjpg_streamer
LIB_DIR=/usr/lib
DEST_DIR=/usr/www
RESOLUTION=320x240
FRAME=10
PID=

if [ "$1" != "" ]
then
    if [ $1 -eq 0 -o $1 -eq 1 ]
    then
        PID=`ps -ef|grep ${MJPG_HOME}|grep ${MJPG_NAME}|grep video"$1"|awk '{print $2}'`
    else
        echo "Usage : sh MjpgStreamerStats.sh 0[1]"
        exit 1
    fi
else
    echo "Usage : sh MjpgStreamerStats.sh 0[1]"
    exit 1
fi

if [ "$PID" = "" ]
then
    case "$1" in

        "0")
        ${MJPG_HOME}/${MJPG_NAME} \
        -i \
        "${LIB_DIR}/input_uvc.so -d /dev/video0  -y -r ${RESOLUTION} -f ${FRAME}" -o "${LIB_DIR}/output_http.so -p 9001 -w ${DEST_DIR}" -b
        exit $?
        ;;

        "1")
        ${MJPG_HOME}/${MJPG_NAME} \
        -i \
        "${LIB_DIR}/input_uvc.so -d /dev/video1  -y -r ${RESOLUTION} -f ${FRAME}" -o "${LIB_DIR}/output_http.so -p 9002 -w ${DEST_DIR}" -b
        exit $?
        ;;

       *)
        echo "Usage : sh MjpgStreamer.sh 0[1]"
        exit 1
        ;;
    esac
else
    echo "Device video"$1" ${MJPG_NAME} Start Error : \"video"$1" ${MJPG_NAME}\" is running !"
fi