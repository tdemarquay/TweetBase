<a href="listTasks.php"> List tasks</a><br/><br/>
<?php
ini_set('display_errors',1);


if(isset($_POST['keywords']))
{

	$track = $_POST['keywords'];
	$parameters = "'".$track."'";

	if(isset($_POST['future']) && $_POST['future']=="future")
	{	
		$command = "python /home/thibault/tweetBase/TweetBase/test.py ".$parameters." > /dev/null 2>/dev/null &";
	}
	else $command ="";

	echo "The executed command is  : ".$command;
	exec("pkill python");
	if(!empty($command))exec($command,$output);
	//print_r($output);
	//print_r($_POST);
}
?>


<h1> Create task</h1>
<div style="text-align:center">
	<form method="post" action="createTask.php">
	   <p>
		   <label for="pseudo">Keywords :</label>
		   <input style="width:500px" type="text" name="keywords" id="pseudo" /><br/>Separate by comma (=OR). <br/>Can have two words or more between two commas (=AND). <br/>Can be a hastag (don't forget the #)
		   

		   <br /><br />
		   <label for="pass">Future tweets :</label>
		   <input type="checkbox" name="future" value="future" checked> 
		  
		   <br /><br />
		   <input type="submit" name="create" value="Create task" id="pass" />
	   </p>

	</form>
</div>
