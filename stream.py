#!/usr/bin/env python

#Import the necessary methods from tweepy library
from tweepy.streaming import StreamListener
from tweepy import OAuthHandler
from tweepy import Stream
from tweepy import API
from datetime import datetime, timedelta
from email.utils import parsedate_tz
from warnings import filterwarnings
import os, sys, tweepy, json
import code
import MySQLdb
filterwarnings('ignore', category = MySQLdb.Warning)
def extract_parameter(parameter) :
	list = []
	if not parameter:
		return list
	list = parameter.split(',')
	return list
	
def to_datetime(datestring):
    time_tuple = parsedate_tz(datestring.strip())
    dt = datetime(*time_tuple[:6])
    return dt - timedelta(seconds=time_tuple[-1])
	
db=MySQLdb.connect(host="localhost",user="tweetbase",passwd=code.bdd_password,db="tweetbase",use_unicode=True,charset='UTF8')
	
def saveUser(user):
	global db
	c=db.cursor()
	#user insert
	if user['location'] is not None:
		location = (user['location']).encode('UTF-8')
	else:
		location = ""
	if user['url'] is not None:
		url = user['url']
	else:
		url = ""
	if user['description'] is not None:
		description = (user['description']).encode('UTF-8')
	else:
		description = ""
	
	sql = "INSERT INTO user VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) ON DUPLICATE KEY UPDATE name=%s, screen_name=%s, location = %s, url = %s, description = %s, followers_count = %s, friends_count = %s, listed_count = %s, favourites_count = %s, statuses_count = %s, created_at = %s"
	
	#print query_user
	#print user['id']
	
	c.execute(sql, (user['id'], (user['name']).encode('UTF-8'), 
	(user['screen_name']).encode('UTF-8'), location, url, description, 
	user['followers_count'], user['friends_count'], 
	user['listed_count'], user['favourites_count'], 
	user['statuses_count'], to_datetime(user['created_at']), 
	(user['name']).encode('UTF-8'), (user['screen_name']).encode('UTF-8'), 
	location, url, description, user['followers_count'], user['friends_count'], 
	user['listed_count'], user['favourites_count'], user['statuses_count'], 
	to_datetime(user['created_at'])))

	db.commit()
	
	
def saveTweet(tweet):
	global db
	c=db.cursor()
	if tweet.get('place'):
		place = (tweet['place']['full_name']).encode('UTF-8')
	else:
		place = ""
	if tweet['in_reply_to_screen_name'] is not None:
		in_reply_to_screen_name = (tweet['in_reply_to_screen_name']).encode('UTF-8')
	else:
		in_reply_to_screen_name = ""
	if tweet['source'] is not None:
		source = (tweet['source']).encode('UTF-8')
	else:
		source = ""
		
	print tweet['user']['id']
	
	sql = "INSERT INTO tweet ( id, task_id, text, created_at, source, in_reply_to_status_id, in_reply_to_user_id, in_reply_to_screen_name, retweet_count, favorite_count, place, user_id)  VALUES ('%s', (SELECT task_id FROM task WHERE state > 0), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
	c.execute(sql, (tweet['id'], (tweet['text']), 
	to_datetime(tweet['created_at']), source, 
	tweet['in_reply_to_status_id'], tweet['in_reply_to_user_id'], 
	in_reply_to_screen_name, tweet['retweet_count'], 
	tweet['favorite_count'], place,
	tweet['user']['id']))
	db.commit()
	
def removeExistingFile():
	if os.path.isfile('/home/thibault/tweetBase/TweetBase/website/workfile'): 
		os.remove('/home/thibault/tweetBase/TweetBase/website/workfile')
		print 'Data file exist, deleting'
	
#This is a basic listener that just prints received tweets to stdout.
class StdOutListener(StreamListener):

    def on_data(self, data):		
		decoded = json.loads(data)
		if 'user' in decoded:
			saveUser(decoded['user'])
			saveTweet(decoded)
		
		return True

    def on_error(self, status):
        print status


if __name__ == '__main__':

	#This handles Twitter authetification and the connection to Twitter Streaming API
	#We need to remove the workfile if exists (for user and tweet data)
	l = StdOutListener()
	auth = OAuthHandler(code.consumer_key, code.consumer_secret)
 	auth.set_access_token(code.access_token, code.access_token_secret)
	#Connection to the stream API
	stream = Stream(auth, l)
	
	removeExistingFile()

	#We check if parameters received are correct
	if len(sys.argv) != 2:
		print "Parameters missing or too more"
		exit(0)
	        	
	#We get the parameters
	keywords = sys.argv[1]
	stream.filter(track=extract_parameter(keywords))
