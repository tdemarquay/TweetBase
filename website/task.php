<?php
include ('bdd.php');
?>

<a href="listTasks.php"> List tasks</a><br/><br/>
<?php
ini_set('display_errors',1);

//If the user wants to create a task
if(isset($_POST['keywords']))
{
	//We get the information of the form
	if(!isset($_POST['user_info'])) $user_info = 0;
	else $user_info = 1;
	$track = $_POST['keywords'];
	$follow = $_POST['user_id'];
	//we concatenate all the parameters
	$parameters = "'".$track."' '".$follow."' ".$user_info;
	
	addTask($track, $follow, $user_info);
	
	//It is not the same command, it depends of it's a future reseach (streaming api) or past research (rest api)
	if(isset($_POST['future']) && $_POST['future']=="future")
	{	
	$command = "python /home/thibault/tweetBase/TweetBase/stream.py ".$parameters." > /home/thibault/tweetBase/TweetBase/output 2>/home/thibault/tweetBase/TweetBase/output &";
//$command = "python /home/thibault/tweetBase/TweetBase/stream.py ".$parameters." 2>&1 &";
	}
	else $command ="";

	//We print the user command for debugging
	echo "The executed command is  : ".$command;
	//If an old process was processing, we kill it
	exec("pkill python");
	//We execute the command. We check that command variable is not empty
	if(!empty($command))exec($command,$output);
	print_r($output);
}
?>


<h1> Create task</h1>
<div style="text-align:center">
	<form method="post" action="task.php">
	   <p>
	    <label for="pseudo">Username :</label>
		   <input style="width:500px" type="text" name="user_id" id="pseudo" /><br/>Separate by comma.<br/><br/> <b>OR</b><br/><br/>
	   
	   
		   <label for="pseudo">Keywords :</label>
		   <input style="width:500px" type="text" name="keywords" id="pseudo" /><br/>Separate by comma (=OR). <br/>Can have two words or more between two commas (=AND). <br/>Can be a hastag (don't forget the #)
		   

		   <br /><br />
 		   <label for="pass">Future tweets :</label>
		   <input type="checkbox" name="future" value="future" checked> 

		  <br /><br />
                   <label for="pass">Download also user information :</label>
                   <input type="checkbox" name="user_info" value="user_info" checked>

		  
		   <br /><br />
		   <input type="submit" name="create" value="Create task" id="pass" />
	   </p>

	</form>
</div>
