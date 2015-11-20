#!/usr/bin/env python

#Import the necessary methods from tweepy library
from tweepy.streaming import StreamListener
from tweepy import OAuthHandler
from tweepy import Stream
from tweepy import API
import os, sys, tweepy, code

def extract_parameter(parameter) :
	list = []
	if not parameter:
		return list
	list = parameter.split(',')
	return list

#This is a basic listener that just prints received tweets to stdout.
class StdOutListener(StreamListener):

    def on_data(self, data):
	f = open('/home/thibault/tweetBase/TweetBase/website/workfile', 'a+')
	f.write(data+'bonjour')
        return True

    def on_error(self, status):
        print status


if __name__ == '__main__':

	#This handles Twitter authetification and the connection to Twitter Streaming API
	#We need to remove the workfile if exists (for user and tweet data)
        if os.path.isfile('/home/thibault/tweetBase/TweetBase/website/user'):
                os.remove('/home/thibault/tweetBase/TweetBase/website/user')
                print 'User file exists, deleting'


	if os.path.isfile('/home/thibault/tweetBase/TweetBase/website/workfile'): 
		os.remove('/home/thibault/tweetBase/TweetBase/website/workfile')
		print 'Data file exist, deleting'
	l = StdOutListener()
	auth = OAuthHandler(consumer_key, consumer_secret)
 	auth.set_access_token(access_token, access_token_secret)
	#Connection to the stream API
	stream = Stream(auth, l)
	#Connection to witter API (needed for user information)
	api = tweepy.API(auth)

	#We check if parameters received are correct
	if len(sys.argv) != 4:
		print "Parameters missing or too more"
		exit(0)
	        	
	#We get the parameters
	keywords = sys.argv[1]
	user_screename = sys.argv[2]
	user_info = sys.argv[3]
	user_id = []
	#We have ton conveert the screen name to user id
	for member in extract_parameter(user_screename):
		user_id.append(str(api.get_user(screen_name = member).id))
	#This line filter Twitter Streams to capture data by the keywords
	stream.filter(track=extract_parameter(keywords), follow=user_id)
