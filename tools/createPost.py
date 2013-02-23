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
import json
import codecs
from datetime import datetime

class postData():
    """
    Defines variables and functions for creating a json post for 
    the blog.
    """
    __slots__=('filename', 'humanDate', 'atomDate', 'content', 'title', 'tags')
    
    def __init__(self, title, tags, infile):
        """
        Constructs the postdata object with the atom date, human-readable
        date, and filename yyyy.mm.dd.json, then pulls in and formats
        post content and other information.
        
        Arguments:
            title (str): the title for the post
            tags (str): a comma separated list of tags (as a string)
            infile (str): the filename of a file to read in for content
        """
        currentDate = datetime.now()
        
        # Sets all the fields that contain some amount of date information
        self.humanDate = currentDate.strftime("%B %d, %Y")
        self.atomDate = str( currentDate.isoformat()[0:-7] ) + "-05:00"
        self.filename = ( currentDate.strftime("%Y.%m.%d")+".json" )
        
        # Reads in a plaintext or semi-html file and adds paragraph
        # formatting tags
        self.content = []
        for line in codecs.open(infile, 'r', 'utf-8'):
            self.content.append("<p>"+line+"</p>\n")
        
        # Sets the title and tags fields    
        self.title = title
        self.tags = tags
        
    def json(self):
        """
        Returns a json-encoded representation of the data contained
        in the post object
        
        Arguments:
            none
        Returns:
            a json dump of the post information
        """
        return json.dumps({'title':self.title, 'datestamp':self.atomDate, 'date':self.humanDate, 'tags':self.tags, 'content':self.content},sort_keys=False, indent=4, separators=(',', ': ') )

        
    def write(self):
        """
        Writes the postData to the file contained in the filename slot
        
        Arguments:
            none
        Returns:
            none
        """
        outfile = open(self.filename, 'w')
        outfile.write( self.json() )
        outfile.close()
        
    def __str__(self):
        """
        Returns a string representation of the contained post data
        
        Arguments:
            none
        Returns:
            (str) containing a human-readable representation of some
                of the contained data (title, date, tags, filename)
        """
        return "Post: " + self.title + "\n date: " + self.atomDate + " (" + self.humanDate + ") \n Tags: " + self.tags + "\n Saving to: " + self.filename


def getfilename():
    """
    Retrieves the name of a file to open from the user and returns
    it if it exists. Otherwise, informs the user that the file could
    not be found and requests a different file.
    
    Arguments:
        none
    Returns:
        filename (str): the name of a file to use
    """
    filename = input("Enter a name of a file to open: ")
    while True:
        try:
            f = open(filename, 'r')
            print("Cool, I got it, using "+filename)
            return filename
        except:
            filename = input("File does not exist: enter a name of a file to open: ")
    
def main():
    """
    Sets a few variables for the input file, title, and tags
    then creates an instance of a postData object which is used to
    generate and save a json format file for the post.
    """
    filename = getfilename()
    title = input("Title: ")
    tags = input("Tags: ")
    post = postData(title, tags, filename)
    
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
    
    
