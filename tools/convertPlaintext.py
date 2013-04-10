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
    from postData import getConfig
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
    
    config = getConfig() 
    # Pulls the post directory from the config and splits it at the
    # www directory so the path can be made relative to the current
    # directory.  This makes it possible to use this program from
    # any system no matter where the web stuff is mounted locally
    postPath = config["curr_path"] + config["post_directory"].split('www')[1]
    
    post = postData('', '', filename, 'convert')
    print("\nRegenerating post:")
    print(post)

    loadNow = input( "Would you like to load the post to the blog now? (enter 'yes' if so): ")
    
    if ( loadNow != 'yes' ):
    # Calls the postData write function to save it in json format
        post.write()
    else:
        post.write( postPath )
    
    print("\nDone.")

# Calls the main function    
main()
    
    
