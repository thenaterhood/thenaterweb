"""
Author: Nate Levesque <public@thenaterhood.com>
Language: Python3
Filename: convertPlaintext.py
Description:
	converts a plaintext post (following the specific syntax) to
	json format
"""
import codecs
try:
    from postData import postData
    from postData import getfilename
except:
    print("The postData class is missing and is required for this program.")
    quit()
    
def main():
    """
    Retrieves the filename of the file to convert from the user
    then uses the postData class to retrieve data from it and
    write it to a json file
    """
    filename = getfilename()
    
    post = postData('', '', filename, 'convert')
    print("\nRegenerating post:")
    print(post)
    post.write()
    print("\nDone.")

# Calls the main function    
main()
    
    
