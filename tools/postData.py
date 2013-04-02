"""
Author: Nate Levesque <public@thenaterhood.com>
Language: Python3
Filename: postData.py
Description:
    common classes and functions for manipulating posts in json
    and plaintext formats
"""
from datetime import datetime
import os
import codecs
import json

class postData():
    """
    Defines variables and functions for creating a json post for 
    the blog.
    """
    __slots__=('filename', 'humanDate', 'atomDate', 'content', 'title', 'tags')
    
    def __init__(self, title, tags, infile, mode):
        """
        Constructs the postdata object with the atom date, human-readable
        date, and filename yyyy.mm.dd.json, then pulls in and formats
        post content and other information.
        
        Arguments:
            title (str): the title for the post
            tags (str): a comma separated list of tags (as a string)
            infile (str): the filename of a file to read in for content
            mode (str): 'create' or 'convert' to create a new post in json
                format or to convert an existing plaintext post to json.
                'convert' overrides all over options as it will pull them
                from the existing plaintext post.
        """
        
        if ( mode == 'create' ):
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
        if ( mode == 'convert' ):
            self.readtext(infile)
        
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

        
    def write(self, path='.'):
        """
        Writes the postData to the file contained in the filename slot
        
        Arguments:
            none
        Returns:
            none
        """
        outfile = open( path+'/'+self.filename, 'w')
        outfile.write( self.json() )
        outfile.close()
    
    def readtext(self, infile):
        """
        Reads in a plaintext file and sets the fields of the class
        to the data it contains.  Syntax of the text file is:
        
         * TITLE
         * DISPLAY DATE
         * TAGS
         * FEED DATESTAMP
         * CONTENT
        
        Arguments:
            infile (str): the name of a file to open and pull from
        Returns:
            none
        """
        fileContents = []
        
        # Reads the contents of the text file into an array
        for line in codecs.open(infile, 'r', 'utf-8'):
            fileContents.append(line)
            
        # Pops lines from the file for the fields (following the syntax
        # for plaintext posts)
        self.title = fileContents.pop(0).strip()
        self.humanDate = fileContents.pop(0).strip()
        self.tags = fileContents.pop(0).strip()
        self.atomDate = fileContents.pop(0).strip()
        self.content = []
        
        for item in fileContents:
            self.content.append('<p>'+item.strip()+"</p>\n")
            
        self.filename = self.atomDate[0:10].replace('-', '.') + '.json'
        
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
            
def getConfig():
    """
    Retrieves the current site configuration
    
    Arguments:
        none
    Returns:
        config (dict): the site config in dictionary form
    """
    currPath = os.getcwd()
    rootPath = os.getcwd() + "/.."
    confPath = rootPath + "/engine/core_config.php"
    config = {}
    
    config[ "curr_path" ] = rootPath
    
    for line in open( confPath ):
        try:
            if ( line.strip()[0] == '$' ):
                confdata =  line[9:-3].strip().split(' = ')
                config[ confdata[0] ] = confdata[1]
        except:
            pass
        
    return config
