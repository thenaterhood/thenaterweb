#!/usr/bin/python3
"""
Author: Nate Levesque <public@thenaterhood.com>
File: testAnal.py
Language: Python3
Description:

"""
import unittest
import json
from urllib.request import urlopen

domain = "192.168.1.103"
url = "http://192.168.1.103/engine/"


class testWebEngine(unittest.TestCase):
	
	def setUp(self):
		return
		
	def test_smoke(self):
		self.assertTrue( True )
		
	def test_sanitizeShort(self):
		# Test the ability to sanitize a short string
		page = urlopen( url+"api_saneString.php?testvar=justtest" ).read().decode('utf-8')
		decodedData = json.loads( page )
		self.assertEqual( decodedData["testvar"], "justtest" )
		
	def test_sanitizeMessy(self):
		# Test the ability to remove non-alphanumberic characters
		page = urlopen( url+"api_saneString.php?testvar=:)just*test$*@(*%" ).read().decode('utf-8')
		decodedData = json.loads( page )
		self.assertEqual( decodedData["testvar"], "justtest" )
		
	def test_sessionDomain(self):
		# Test that the session class contains expected data
		page = urlopen( url+"api_session.php" ).read().decode('utf-8')
		decodedData = json.loads( page )
		self.assertEqual( decodedData["domain"], domain )
		
	def test_sessionData(self):
		# Test that the session class can pick up data
		page = urlopen( url+"api_session.php?name=test").read().decode('utf-8')
		decodedData = json.loads(page)
		self.assertEqual( decodedData["name"], "test" )
		
	def test_sessionDataMessy(self):
		# Test that the session class can pick up messy data correctly
		page = urlopen( url+"api_session.php?name=te$%@!st").read().decode('utf-8')
		decodedData = json.loads(page)
		self.assertEqual( decodedData["name"], "test" )
		
	def test_postObj_missing(self):
		# Test that the postObj class can return data and deal with missing posts
		page = urlopen( url+"api_post.php?node=test&element=title").read().decode('utf-8')
		decodedData = json.loads(page)
		self.assertEqual( decodedData["title"], "Oops! Post Not Found!" )



if __name__ == "__main__":
    unittest.main()
