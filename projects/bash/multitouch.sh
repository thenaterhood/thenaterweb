#!/bin/bash
#
################################################
# Enables multitouch features for trackpad
# 2010
# Nate Levesque (public@thenaterhood.com)
################################################
#
sleep 5
xinput set-int-prop "SynPS/2 Synaptics TouchPad" "Two-Finger Scrolling" 8 1
xinput set-int-prop "SynPS/2 Synaptics TouchPad" "Synaptics Two-Finger Scrolling" 8 1 1
xinput set-int-prop "SynPS/2 Synaptics TouchPad" "Synaptics Two-Finger Pressure" 32 10
xinput set-int-prop "SynPS/2 Synaptics TouchPad" "Synaptics Two-Finger Width" 32 8
xinput set-int-prop "SynPS/2 Synaptics TouchPad" "Synaptics Circular Scrolling" 8 1
xinput set-int-prop "SynPS/2 Synaptics TouchPad" "Synaptics Palm Detection" 8 1
xinput set-int-prop "SynPS/2 Synaptics TouchPad" "Synaptics Scrolling Distance" 32 200 200
xinput set-prop --type=float "SynPS/2 Synaptics TouchPad" "Synaptics Circular Scrolling Distance" 0.4
exit 0
