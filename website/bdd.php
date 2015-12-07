<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=tweetbase;charset=utf8', 'tweetbase', 'TWEETBASE');
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

//Add a task in the database
function addTask($keywords, $users, $user_info)
{
	global $bdd;

	$req = $bdd->prepare('INSERT INTO task(task_start_datetime, task_end_datetime, keywords, users, user_information, state) VALUES(NOW(), :task_end_datetime, :keywords, :users,  :user_information, 1)');
	
	$req->execute(array(
		'task_end_datetime' => 0, 
		'keywords' => $keywords, 
		'users' => $users,
		'user_information' => $user_info
    	));
	echo 'Task created !<br/>';
}

//Know if a task is running
function isATaskRunning()
{
	global $bdd;
	$result = $bdd->query("SELECT COUNT(*) FROM task WHERE state > 0");
	if($result->fetchColumn()>0) return true;
	else return false;
}

//Know if a task is în Pause
function isATaskInPause()
{
        global $bdd;
        $result = $bdd->query("SELECT COUNT(*) FROM task WHERE state = 2");
        if($result->fetchColumn()>0) return true;
        else return false;
}


//Get task id of running task
function getCurrentTaskId()
{
	global $bdd;
	$result = $bdd->query("SELECT task_id FROM task WHERE state > 0");
	return $result->fetch()['task_id'];
}

function getTaskKeywords($id)
{
	global $bdd;
	$result = $bdd->query("SELECT keywords FROM task WHERE task_id = ".$id);
	return $result->fetch()['keywords'];
}

//Return the users id follow of this task
function getTaskUserId($id)
{
	global $bdd;
        $result = $bdd->query("SELECT users FROM task WHERE task_id = ".$id);
        return $result->fetch()['users'];
}

//Return true if there is user infi needed info for this task or false if not
function getTaskUserInfo($id)
{
	global $bdd;
	if(!is_null($id) && !empty($id))
	{
		$result = $bdd->query("SELECT user_information FROM task WHERE task_id = ".$id);
        if($result->fetch()['user_information'] == 1) return true; else return false;
	}
}

//Stop all current task
function  stopAllTask()
{
	global $bdd;
	$result = $bdd->query("UPDATE task SET state = 0");
}

//Resume all current task
function  resumeTask()
{
        global $bdd;
        $result = $bdd->query("UPDATE task SET state = 1 WHERE state=2");
}

//Stop all current task
function  pauseTask()
{
        global $bdd;
        $result = $bdd->query("UPDATE task SET state = 2 WHERE state = 1");
}

//Add an user
function saveUser($users)
{	
	global $bdd;
	$req = $bdd->prepare('INSERT INTO user(user_id, name, screen_name, location, url, description, followers_count, friends_count, listed_count, favourites_count, statuses_count, created_at)
 VALUES(:user_id, :name, :screen_name, :location, :url, :description, :followers_count, :friends_count, :listed_count, :favourites_count, :statuses_count, :created_at)
 ON DUPLICATE KEY UPDATE name = :name2, screen_name = :screen_name2, location = :location2, url = :url2, description = :description2, followers_count = :followers_count2, 
friends_count = :friends_count2, listed_count = :listed_count2, favourites_count = :favourites_count2, statuses_count = :statuses_count2, created_at = :created_at2');
	
	$user_id = (isset($users['id'])) ? $users['id'] : 0;
	$name = (isset($users['name'])) ? $users['name'] : '';
	$screen_name = (isset($users['screen_name'])) ? $users['screen_name'] : '';
	$location = (isset($users['location'])) ? $users['location'] : '';
	$url = (isset($users['url'])) ? $users['url'] : '';
	$description = (isset($users['description'])) ? $users['description'] : '';
	$followers_count = (isset($users['followers_count'])) ? $users['followers_count'] : 0;
	$friends_count = (isset($users['friends_count'])) ? $users['friends_count'] : 0;
	$listed_count = (isset($users['listed_count'])) ? $users['listed_count'] : 0;
	$favourites_count = (isset($users['favourites_count'])) ? $users['favourites_count'] : 0;
	$statuses_count = (isset($users['statuses_count'])) ? $users['statuses_count'] : 0;
	$created_at = (isset($users['created_at'])) ? DateTime::createFromFormat('D M d H:i:s P Y',  (string)$users['created_at'])->format('Y-m-d H:i:s') : 0;
	
	$req->execute(array(
		'user_id'=> $user_id,
		'name'=> $name,
		'screen_name'=> $screen_name,
		'location'=> $location,
		'url'=> $url,
		'description'=> $description,
		'followers_count'=> $followers_count,
		'friends_count'=> $friends_count,
		'listed_count'=> $listed_count,
		'favourites_count'=> $favourites_count,
		'statuses_count'=> $statuses_count,
		'created_at' => $created_at,
                'name'=> $name,
                'screen_name'=> $screen_name,
                'location'=> $location,
                'url'=> $url,
                'description'=> $description,
                'followers_count'=> $followers_count,
                'friends_count'=> $friends_count,
                'listed_count'=> $listed_count,
                'favourites_count'=> $favourites_count,
                'statuses_count'=> $statuses_count,
                'created_at' => $created_at,
                'name2'=> $name,
                'screen_name2'=> $screen_name,
                'location2'=> $location,
                'url2'=> $url,
                'description2'=> $description,
                'followers_count2'=> $followers_count,
                'friends_count2'=> $friends_count,
                'listed_count2'=> $listed_count,
                'favourites_count2'=> $favourites_count,
                'statuses_count2'=> $statuses_count,
                'created_at2' => $created_at

		));
		
	return $bdd->lastInsertId();
}

//Add an user
function saveUser2($user_id, $name, $screen_name, $location, $url, $description, $followers_count, $friends_count, $listed_count, $favourites_count, $statuses_count, $created_at)
{	
	global $bdd;
	$req = $bdd->prepare('INSERT INTO user(user_id, name, screen_name, location, url, description, followers_count, friends_count, listed_count, favourites_count, statuses_count, created_at)
 VALUES(:user_id, :name, :screen_name, :location, :url, :description, :followers_count, :friends_count, :listed_count, :favourites_count, :statuses_count, :created_at)
 ON DUPLICATE KEY UPDATE name = :name2, screen_name = :screen_name2, location = :location2, url = :url2, description = :description2, followers_count = :followers_count2, 
friends_count = :friends_count2, listed_count = :listed_count2, favourites_count = :favourites_count2, statuses_count = :statuses_count2, created_at = :created_at2');
	
	//$user_id = (isset($users['id'])) ? $users['id'] : 0;
	//$name = (isset($users['name'])) ? $users['name'] : '';
	//$screen_name = (isset($users['screen_name'])) ? $users['screen_name'] : '';
	///$location = (isset($users['location'])) ? $users['location'] : '';
	//$url = (isset($users['url'])) ? $users['url'] : '';
	//$description = (isset($users['description'])) ? $users['description'] : '';
	//$followers_count = (isset($users['followers_count'])) ? $users['followers_count'] : 0;
	//$friends_count = (isset($users['friends_count'])) ? $users['friends_count'] : 0;
	//$listed_count = (isset($users['listed_count'])) ? $users['listed_count'] : 0;
	//$favourites_count = (isset($users['favourites_count'])) ? $users['favourites_count'] : 0;
	//$statuses_count = (isset($users['statuses_count'])) ? $users['statuses_count'] : 0;
	$created_at = (isset($created_at)) ? DateTime::createFromFormat('D M d H:i:s P Y',  (string)$created_at)->format('Y-m-d H:i:s') : 0;
	
	$req->execute(array(
		'user_id'=> $user_id,
		'name'=> $name,
		'screen_name'=> $screen_name,
		'location'=> $location,
		'url'=> $url,
		'description'=> $description,
		'followers_count'=> $followers_count,
		'friends_count'=> $friends_count,
		'listed_count'=> $listed_count,
		'favourites_count'=> $favourites_count,
		'statuses_count'=> $statuses_count,
		'created_at' => $created_at,
                'name'=> $name,
                'screen_name'=> $screen_name,
                'location'=> $location,
                'url'=> $url,
                'description'=> $description,
                'followers_count'=> $followers_count,
                'friends_count'=> $friends_count,
                'listed_count'=> $listed_count,
                'favourites_count'=> $favourites_count,
                'statuses_count'=> $statuses_count,
                'created_at' => $created_at,
                'name2'=> $name,
                'screen_name2'=> $screen_name,
                'location2'=> $location,
                'url2'=> $url,
                'description2'=> $description,
                'followers_count2'=> $followers_count,
                'friends_count2'=> $friends_count,
                'listed_count2'=> $listed_count,
                'favourites_count2'=> $favourites_count,
                'statuses_count2'=> $statuses_count,
                'created_at2' => $created_at

		));
		
	return $bdd->lastInsertId();
}

//Insert a tweet
function saveTweet($tweet, $task_id)
{	
	global $bdd;
	$req = $bdd->prepare('INSERT INTO tweet( id, task_id, text, created_at, source, in_reply_to_status_id, in_reply_to_user_id, in_reply_to_screen_name, retweet_count, favorite_count, coordinates, place, geo, user_id) 
	VALUES(:id, :task_id, :text, :created_at, :source, :in_reply_to_status_id, :in_reply_to_user_id, :in_reply_to_screen_name, :retweet_count, :favorite_count, :coordinates, :place, :geo, :user_id)');
	
	//Prepara data	
	$user_id = (isset($tweet['user']['id'])) ? $tweet['user']['id'] : 0;
	$id = (isset($tweet['id'])) ? $tweet['id'] : 0;
	$text = (isset($tweet['text'])) ? $tweet['text'] : '';
	$created_at = (isset($tweet['created_at'])) ?  DateTime::createFromFormat('D M d H:i:s P Y',  (string)$tweet['created_at'])->format('Y-m-d H:i:s') : 0;
	$source = (isset($tweet['source'])) ? $tweet['source'] : '';
	$in_reply_to_status_id = (isset($tweet['in_reply_to_status_id'])) ? $tweet['in_reply_to_status_id'] : 0;
	$in_reply_to_user_id = (isset($tweet['in_reply_to_user_id'])) ? $tweet['in_reply_to_user_id'] : 0;
	$in_reply_to_screen_name = (isset($tweet['in_reply_to_screen_name'])) ? $tweet['in_reply_to_screen_name'] : '';
	$retweet_count = (isset($tweet['retweet_count'])) ? $tweet['retweet_count'] : 0;
	$favorite_count = (isset($tweet['favorite_count'])) ? $tweet['favorite_count'] : 0;
	$coordinates = (isset($tweet['coordinates'])) ? $tweet['coordinates'] : '';
	$place = (isset($tweet['place'])) ?$tweet['place'] : '';
	$geo = (isset($tweet['geo'])) ? $tweet['geo'] : '';
	//$sql = "INSERT INTO tweet( id, task_id, text, created_at, source, in_reply_to_status_id, in_reply_to_user_id, in_reply_to_screen_name, retweet_count, favorite_count, coordinates, place, geo, user_id) ".
        //" VALUES(".$id.", ".$task_id.", '".$text."', '".$created_at."', '".$source."', ".$in_reply_to_status_id.", ".$in_reply_to_user_id.", '".$in_reply_to_screen_name."', ".$retweet_count.", ".$favorite_count.", '".$coordinates."', '".$place."', '".$geo."', ".$user_id.")";
	print_r($id);
	$req->execute(array(
		'id'=> $id,
		'task_id'=> $task_id,
		'text'=> $text,
		'created_at'=> $created_at,
		'source'=> $source,
		'in_reply_to_status_id'=> $in_reply_to_status_id,
		'in_reply_to_user_id'=> $in_reply_to_user_id,
		'in_reply_to_screen_name'=> $in_reply_to_screen_name,
		'retweet_count'=> $retweet_count,
		'favorite_count'=> $favorite_count,
		'coordinates'=> $coordinates,
		'place' => $place,
		'geo' => $geo,
		'user_id' => $user_id
		));
		
}

//Finish current task
function endCurrentTask()
{
	global $bdd;

	$bdd->query('UPDATE task SET task_end_datetime=NOW() WHERE state > 0');
}

//Get number of tweets for a task
function getNumberTweets($task_id) 
{
	global $bdd;
	if(!empty($task_id) AND !is_null($task_id))
	{
		$result = $bdd->query("SELECT COUNT(*) FROM tweet WHERE task_id= ".$task_id);
		return $result->fetch()['COUNT(*)'];
	}
	else return 0;

}


//Get task
function getTasks()
{
	global $bdd;
	$result = $bdd->query("SELECT * FROM task");
	return $result->fetchAll();
}

//Dete a task
function deleteTask($task_id)
{
	global $bdd;

	//$result = $bdd->query("SELECT user_id FROM tweet WHERE  task_id = ".$task_id);
	//$results = $result->fetchAll();
	//foreach($results as $res)
		//$bdd->exec("DELETE FROM user WHERE id = ".$res['user_id']);
	//$bdd->exec("DELETE FROM tweet WHERE task_id = ".$task_id);
	$bdd->exec("DELETE FROM task WHERE task_id = ".$task_id);

}

function generateCSV($id)
{
	global $bdd;
	$bdd->query('SELECT * INTO OUTFILE "/home/thibault/tweetBase/TweetBase/website/mydata.csv"
	FIELDS TERMINATED BY \',\' OPTIONALLY ENCLOSED BY \'"\'
	LINES TERMINATED BY "\n"
	FROM task,tweet,user 
	WHERE tweet.user_id = user.user_id AND tweet.task_id = task.task_id AND task.state = 0 AND task.task_id='.$id);
}

//delete all data
function purge()
{
	global $bdd;
	$bdd->exec("DELETE FROM user");
	$bdd->exec("DELETE FROM tweet");
	$bdd->exec("DELETE FROM task");
}
?>

