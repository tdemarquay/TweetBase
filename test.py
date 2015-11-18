#Import the necessary methods from tweepy library
from tweepy.streaming import StreamListener
from tweepy import OAuthHandler
from tweepy import Stream

#Variables that contains the user credentials to access Twitter API 
access_token = "69917660-SBdzu887lwdyc4J51VWsTnnbqWE9jLyvUtwAa6wWg"
access_token_secret = "Iknrq4yjZPxxw9QvJBcMkDGiKEvdJoc4UQ2D5h5X9hunl"
consumer_key = "DcH2VKtNmaBYuykyrLrT9r6fc"
consumer_secret = "bEOA1g3odwRTYc8sSmmTnHItpHtGWACLpey5J6iQVXQjMcsEKO"


#This is a basic listener that just prints received tweets to stdout.
class StdOutListener(StreamListener):

    def on_data(self, data):
        print data
        return True

    def on_error(self, status):
        print status


if __name__ == '__main__':

    #This handles Twitter authetification and the connection to Twitter Streaming API
    l = StdOutListener()
    auth = OAuthHandler(consumer_key, consumer_secret)
    auth.set_access_token(access_token, access_token_secret)
    stream = Stream(auth, l)

    #This line filter Twitter Streams to capture data by the keywords: 'python', 'javascript', 'ruby'
    stream.filter(track=['python', 'javascript', 'ruby'])
