<?php
ini_set('display_errors',1);
if(isset($_POST['keywords']))
{
	$command = "python /home/thibault/tweetBase/TweetBase/test.py test > /dev/null 2>/dev/null &";
	exec("pkill python");
	exec($command,$output);
	print_r($output);
	print_r($_POST);
}
?>

?>
<a href="listTasks.php"> List tasks</a><br/><br/>

<h1> Create task</h1>
<div style="text-align:center">
	<form method="post" action="createTask.php">
	   <p>
		   <label for="pseudo">Keywords :</label>
		   <input type="text" name="keywords" id="pseudo" />
		   
		   <br /><br />
		   <label for="pass">Hashtags :</label>
		   <input type="text" name="hashtags" id="pass" />

		   <br /><br />
		   <label for="pass">Future tweets :</label>
		   <input type="checkbox" name="future" value="future" checked disabled> 
		  
		   <br /><br />
		   <input type="submit" name="create" value="Create task" id="pass" />
	   </p>

	</form>
</div>
