#!/bin/bash
#
# Author: Nate Levesque <ngl3477@rit.edu>
# Language: Shell
# Filename: test.sh
#
# Description:
#   performs a series of tests 
#

i=0
runTest(){
	(( i++ ))
	echo "===> $1"
	diff <(curl http://192.168.1.103/$1) <(cat $1_expected)
}

# tests temporarily removed, as program doesn't always produce the output
# in the same order, so diffing it doesn't work

echo "Beginning unit and regression testing..."
python3 testEngine.py
echo "======================================================================"
echo "Done with unit and regression testing. Continuing to userspace testing."
echo "Userspace testing checks to see if PHP displays any errors on the page."
echo ""
python3 testUserspace.py
