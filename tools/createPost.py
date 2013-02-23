"""
Author: Nate Levesque <public@thenaterhood.com>
Language: Python3
Filename: createPost.py
Description:
    Generates a json file from a post for use with the website blog
    platform.  Reads in a semi-html file (formatting only, no <p> or
    newlines), adds paragraph tags, and generates a json file containing
    the post information with the current date.
"""
try:
    from postData import postData
    from postData import getfilename
except:
    print("The postData class is missing and is required for this program.")
    quit()
    
def main():
    """
    Sets a few variables for the input file, title, and tags
    then creates an instance of a postData object which is used to
    generate and save a json format file for the post.
    """
    filename = getfilename()
    title = input("Title: ")
    tags = input("Tags: ")
    post = postData(title, tags, filename, 'create')
    
    # Prints the post for the user
    print("\n")
    print("Post generated: \n")
    print(post)
    
    # Calls the postData write function to save it in json format
    post.write()
    
    # Informs the user that the program has finished
    print("\nDone.")

# Calls the main method   
main()
    
    
