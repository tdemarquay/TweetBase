<a href="listTasks.php"> List tasks</a><br/><br/>
<?php
ini_set('display_errors',1);


if(isset($_POST['keywords']))
{

	$track = $_POST['keywords'];
	$follow = $_POST['user_id'];
	$parameters = "'".$track."' '".$follow."'";

	if(isset($_POST['future']) && $_POST['future']=="future")
	{	
	$command = "python /home/thibault/tweetBase/TweetBase/stream.py ".$parameters." > /home/thibault/tweetBase/TweetBase/output 2>/home/thibault/tweetBase/TweetBase/output &";
//$command = "python /home/thibault/tweetBase/TweetBase/stream.py ".$parameters." 2>&1 &";
	}
	else $command ="";

	echo "The executed command is  : ".$command;
	exec("pkill python");
	if(!empty($command))exec($command,$output);
	print_r($output);
	//print_r($_POST);
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
