#!/usr/bin/python3
"""
Author: Nate Levesque <public@thenaterhood.com>
File: testAnal.py
Language: Python3
Description:

"""
import sys
from urllib.request import urlopen
from time import clock
import random
import string

def readTests( fname ):
    """
    Reads a test information file into a list and parses it.
    
    Arguments: 
        fname (str): the name of the file to open
    Returns:
    
    """
    testVars = []
    testPages = []
    for line in open( fname ):
        if ( line.split()[0] == 'var' ):
            testVars.append( line.split()[1] )
        if ( line.split()[0] == 'page' ):
            testPages.append( line.split()[1] )
        if ( line.split()[0] == 'logfile' ):
            logfile = line.split()[1]
        if ( line.split()[0] == 'address' ):
            address = line.split()[1]
    return testVars, testPages, address, logfile

    
def buildTests( siteUrl, Pages, Vars, Inputs ):
    """
    Builds a series of URLs to fetch to attempt
    to crash the PHP scripts
    
    Arguments:
        siteUrl (string): the site's web address
        Pages (list): a list of pages to hit
        Vars (list): a list of variables to try on each page
        Inputs (list): a list of values to try for each variable
    Returns:
        testLineup (list): a list of urls to fetch
    """
    testLineup = []
    #for i in range(0, 100):
    #    testLineup.append( siteUrl + random.choice( Pages ) + '?' + random.choice( Vars ) + '=' + random.choice( Inputs ) )
    for page in Pages:
        for var in Vars:
            for data in Inputs:
                testLineup.append( siteUrl + page + '?' + var + '=' + data )

    
    return testLineup

def buildInputs():
    """
    Builds a list of inputs to try for each variable on the site.
    
    Arguments:
        none
    Returns:
        testInputs (list): a list of inputs
    """
    testInputs = ['True', 'False', '1234', '[0]=1']
    garbageCharacters = string.ascii_letters+string.digits
    
    testInputs.append(''.join(random.choice(garbageCharacters) for _ in range(50)) )
    
    return testInputs
    
def runTest( address ):
    """
    Runs a single test to check the address given
    in an argument.
    
    Arguments:
        address (str): the address to fetch (with variables)
    Returns:
        int -1, 0, 1: couldn't retrieve, ok, test found a problem
    """
    try:
        page = urlopen( address ).read().decode('utf-8')
        if ( '<b>Warning</b>:' in page ):
            print( "Found warnings on " + address )
            return 1
        else:
            return 0
    except:
        print("Error retrieving page " + address )
        return -1
        
def main():
    """
    Gathers data to test a website then runs the constructed tests.
    
    This is a USERSPACE test only, but due to the simplicity of the
    site is an effective test.
    
    Arguments:
        none
    Returns:
        none
    """    
    
    # Gather testing data
    checkVars, checkPages, siteUrl, logFile = readTests( "testData" )
    
    checkInputs = buildInputs()
    testLineup = buildTests( siteUrl, checkPages, checkVars, checkInputs )
    
    initialTime = clock()
    
    logOut = open(logFile, 'w')
    
    # Tell the user what's going on
    print( "Starting tests.  Expecting " + str( len( testLineup ) ) + " tests to run." )
    errorsFound = 0
    testsComplete = 0
    
    # Run each test in sequence and check the result
    for test in testLineup:
        status = runTest( test )
        if ( status == 0 ):
            logOut.write( "Test completed successfully: " + test + "\n")
            testsComplete += 1
        if ( status == 1 ):
            logOut.write( "===> POTENTIAL PROBLEM: " + test +"\n")
            errorsFound += 1
            testsComplete += 1
        if ( status == -1 ):
            logOut.write( "Could not retrieve " + test +"\n")
     
    # Close the log file and get the end time of the test run       
    logOut.close()
    endTime = clock()
    
    # Print the results to the console
    print( "\n=======================================\n" )
    print( "Tests completed.")
    print( "Performed " + str( testsComplete ) + " tests (" + str( (testsComplete/len(testLineup)) * 100) + "% of expected)" )
    print( "Elapsed time: " + str(endTime - initialTime) + " minutes")
    print( "Discovered " + str(errorsFound) + " potential problems (" +str( errorsFound/testsComplete * 100) +"% of tests produced a problem)\n" )

if __name__ == "__main__":
    main()
