import json
import codecs
def getfilename():
    return input("Enter a name of a file to open: ")
    
def getSaveName():
    return input("Enter a name to save the file to: ")
    
def readFile(filename):
    fileContents = []
    for line in codecs.open(filename, 'r', 'utf-8'):        
        fileContents.append(line)
    return fileContents
    
def parseFile(fileContents):
    title = fileContents.pop(0).strip()
    dispdate = fileContents.pop(0).strip()
    tags = fileContents.pop(0).strip()
    datestamp = fileContents.pop(0).strip()
    content = []
    
    for item in fileContents:
        content.append('<p>'+item.strip()+"</p>\n")
    
    return title,dispdate,tags,datestamp,content
    
    
    
def encodeJSON(title, datestamp, contents, date, tags):
    encoded = json.dumps({'title':title, 'datestamp':datestamp, 'date':date, 'tags':tags, 'content':contents},sort_keys=False,            indent=4, separators=(',', ': ') )
    return encoded
    
def main():
    filename = getfilename()
    fileContents = readFile(filename)
    title,date,tags,datestamp,content = parseFile(fileContents)
    encoded = encodeJSON(title, datestamp, content, date, tags)
    
    filename = filename + ".json"
    savefile = open(filename, 'w')
    for item in encoded:
        savefile.write(item)
        
    savefile.close()
    
main()
    
    
