#!/bin/bash
###############################
# Nate Levesque (public@thenaterhood.com)
# 2011
###############################
# Pulls files from a public dropbox folder and prints them on a mac
# command line.
#
# To do this manually, the commands are:
# curl -O http://www.thenaterhood.com/file.pdf
# lp -d "mcx_0" file.pdf
# 
# Set those variables!
dropbox_id=
baseUrl=http://dl.dropbox.com/u
#
read -p "all the filenames of the pdfs to be printed, separated by commas, ie file1.pdf,file2.pdf: " files
echo $files | sed -e 's/,/\
/g' > print_list
# downloads the files
for line in `cat print_list`; do
 echo "Downloading $line"
 curl -O $baseUrl/$dropbox_id/$line
done
echo "Downloads done. Printing..."
for file in `ls |grep .pdf`; do
 echo "$file sent to printer"
 lp -d "mcx_0" $file
done
echo "Should all be good."
echo ""
echo "Cleaning up..."
for file in `ls |grep .pdf`; do
 rm $file
 echo "Deleted $file"
done
rm print_list
echo "All done."
