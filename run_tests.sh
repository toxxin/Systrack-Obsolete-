#!/bin/bash
# directory to write output XML (if this doesn't exist, the results will not be generated!)
# OUTPUT_DIR="../test-reports"
# mkdir $OUTPUT_DIR

echo "Start testing...";

JSTESTDRIVER_PATH="/Users/anton/jstestdriver"
JSTESTDRIVER="JsTestDriver-1.3.5.jar"
SOURCE_PATH="/Users/anton/Documents/Source/systrack"
PORT=9876

PLATFORM=`uname -a`
ARCH=`uname -p`
MACH=`uname -m`

# TODO:: Doesn't work and Chrome fails
CHROME="/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome"
OPERA="/Applications/Opera.app/Contents/MacOS/Opera"
SAFARI="/Applications/Safari.app/Contents/MacOS/Safari"
SAFARI_DEV="/Applications/Safari.app/Contents/MacOS/SafariForWebKitDevelopment"

# Defining browsers
# chrome
# firefox
# opera
# safari

# XVFB=`which Xvfb`
# if [ "$?" -eq 1 ];
# then
#     echo "Xvfb not found."
#     exit 1
# fi

# FIREFOX=`which firefox`
# if [ "$?" -eq 1 ];
# then
#     echo "Firefox not found."
#     exit 1
# fi

# CHROME=`which chrome`
# if [ "$?" -eq 1 ];
# then
# 	echo "Chrome not found."
# #	exit 1
# fi

java -jar ${JSTESTDRIVER_PATH}/${JSTESTDRIVER} --tests all

# run the tests
# java -jar ${JSTESTDRIVER_PATH}/${JSTESTDRIVER} --port ${PORT} \
# --browser ${SAFARI},${OPERA},${CHROME} \
# --tests all


