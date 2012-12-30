#!/bin/bash
#
################################
# Nate Levesque
# 8/29/2011
# Script to rip audio from a directory of videos
#
################################
# Prefix for videos to be ripped
#
read -p "Name prefix for videos to be ripped (anything without this will be ignored): " prefix
echo "Ripping from videos in `pwd`"
echo "`ls | grep $prefix*.*`"
if [ "`ls | grep $prefix*.*`" = "" ]; then
echo "Nothing to rip, exiting..."
exit 0
else
sleep 1
for file in $prefix*.* ; do
  ffmpeg -i $file -acodec copy A$file.aac
done
mkdir ripped
mv $prefix* ripped/
read -p "Keep original videos y/n? " KP
if [ $KP = n ]; then
rm -r ripped
echo "Erased..."
elif [ $KP = y ]; then
echo "Keeping..."
fi
exit 0