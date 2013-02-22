#!/bin/bash
#
# Author: Nate Levesque <public@thenaterhood.com>
# Language: Shell
# Filename: importUpdate.sh
#
# Description:
#   recurses through a directory and updates every file with a string 
#   matching oldImport to newImport.  Note that although this is geared
#   towards updating import statements, it can update ANY string in
#   all the files.  It works in the path it's called from. Type carefully.
#
#
read -p "Enter old import path: " oldImport
read -p "Enter new import path: " newImport
echo -e "\nFinding all files in `pwd` to update imports.\n"
read -p "Hit enter to confirm, ctrl+c to cancel"

# Finds everything that is stored in the current directory and
# subdirectories
for file in `find *`; do
    #
    # Check if the item is a directory and update it if it isn't
    if [ ! -d $file ]; then
        #
        # Checks if the string is in the file to avoid needlessly
        # piping files through sed
        if [ "`cat $file | grep "$oldImport"`" != "" ]; then
            #
            # Update the file and output it to a new file
            echo "Updating imports in $file"
            cat $file | sed -e s:"$oldImport":"$newImport":g > $file-updated
            #
            # Move the new file to the old file (end up deleting contents
            # of the file if editing it directly)
            mv $file-updated $file
        fi
    fi
done
