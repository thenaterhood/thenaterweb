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
		decodedData = getData( url+"api_saneString.php?testvar=justtest" )
		self.assertEqual( decodedData["testvar"], "justtest" )
		
	def test_sanitizeMessy(self):
		# Test the ability to remove non-alphanumberic characters
		decodedData = getData( url+"api_saneString.php?testvar=)just*test$*@(*%" )
		self.assertEqual( decodedData["testvar"], "justtest" )
		
	def test_sessionDomain(self):
		# Test that the session class contains expected data
		decodedData = getData( url+"api_session.php" )
		self.assertEqual( decodedData["domain"], domain )
		
	def test_sessionData(self):
		# Test that the session class can pick up data
		decodedData = getData( url+"api_session.php?name=test" )
		self.assertEqual( decodedData["name"], "test" )
		
	def test_sessionDataMessy(self):
		# Test that the session class can pick up messy data correctly
		decodedData = getData( url+"api_session.php?name=te$%@!st&konami=pride" )
		self.assertEqual( decodedData["name"], "test" )
		
	def test_article_missing(self):
		# Test that the article class can return data and deal with missing posts
		decodedData = getData( url+"api_post.php?node=test&element=title" )
		self.assertEqual( decodedData["title"], "Oops! Post Not Found!" )

	def test_inventory_retrieve(self):
		# Test that an item can be retrieved from the inventory with the proper data
		decodedData = getData( url+"api_inventory.php")
		self.assertEqual( decodedData["title"], "Lovin' those Facebook Likes")
		self.assertEqual( decodedData["tags"], "facebook, privacy, social networking")

	def test_redirect(self):
		# Test that a redirect can be correctly initialized
		decodedData = urlopen( url+"api_redirect.php?from=origin&to=destination").read().decode('utf-8')
		self.assertEqual( decodedData, "origin to destination")

def getData( address ):
	page = urlopen( address ).read().decode('utf-8')
	return json.loads(page)

if __name__ == "__main__":
    unittest.main()
