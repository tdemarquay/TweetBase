<?php
include ('bdd.php');
include('functions.php');
?>
<head>
<title> Current/Create task</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
function getLog() {
	setInterval(function() {
		$.get( "count.php", function( data ) {
			 $('#results_nb').text(data);	
		});

	}, 1000);

	setInterval(function() {
    		$('#error_textarea').load('output');
	}, 1000);
}

</script>
</head>
<body onload="getLog()">
<a href="listTasks.php"> List tasks</a><br/><br/>
<?php
ini_set('display_errors',1);

//Change state of task
if(isset($_GET['pause'])) 
{
	pauseTask();
	exec("pkill -STOP python");
	echo "Task paused";
}
if(isset($_GET['resume']))
{
	resumeTask();
	exec("pkill -CONT python");
	echo "Task resumed";
}
if(isset($_GET['stop']))
{
	//Kill process
	exec("pkill  python");
	//readFileAndSave(getTaskUserInfo(getCurrentTaskId()));
	//Update end time
	endCurrentTask();
	stopAllTask();
	if(file_exists("workfile"))unlink("workfile");
	if(file_exists("output"))unlink("output");
	 if(file_exists("user"))unlink("users");
	echo "Task stopped and data saved in database";
}



//If the user wants to create a task
if(isset($_POST['new']))
{
	//Not possible case but a checking must be done
	stopAllTask();
	//We get the information of the form
	if(!isset($_POST['user_info'])) $user_info = 0;
	else $user_info = 1;
	$track = $_POST['keywords'];
	$follow = '';
	//we concatenate all the parameters
	//$parameters = "'".$track."' '".$follow."' ".$user_info;
	$parameters = "'".$track."'";
	
	addTask($track, $follow, $user_info);
		
	$command = "python /home/thibault/tweetBase/TweetBase/stream.py ".$parameters." 1> /home/thibault/tweetBase/TweetBase/website/output 2>/home/thibault/tweetBase/TweetBase/website/output &";

		//$command = "python /home/thibault/tweetBase/TweetBase/stream.py ".$parameters." >>/home/thibault/tweetBase/TweetBase/website/output  2>&1 &";

	
	//We print the user command for debugging
	echo "<br/>The executed command is  : ".$command."<br/><br/>";
	//If an old process was processing, we kill it
	exec("pkill python");
	//We execute the command. We check that command variable is not empty
	if(!empty($command))exec($command,$output);
	//print_r($output);
}

//We check if a taskis running
if(isATaskRunning())
{
	echo "<h1>Current task</h1>"; 
	$disabled = "disabled";
	$current_task = getCurrentTaskId();
	$keywords = getTaskKeywords($current_task);
	$user_id = getTaskUserId($current_task);
	if(getTaskUserInfo($current_task)) $user_info = "checked"; else $user_info ="";
}
else 
{
	echo "<h1>Create a task</h1>";
	$disabled = "";
	$current_task = -1;
	$keywords = "";
	$user_id = "";
	$user_info = "checked";
	
}
?>

<div style="text-align:center">
	<?php if(!isATaskRunning()) echo '<form method="post" action="task.php">'; ?>
	   <p>
		<?php if(!isATaskRunning()) echo '<input type="hidden" name="new">'; ?>
	    <!--<label for="pseudo">Username :</label>-->
		
	   
		   <label for="pseudo">Keywords :</label>
		   <input style="width:500px" value="<?php echo $keywords; ?>" <?php echo $disabled?> type="text" name="keywords" id="pseudo" /><br/>Separate by comma (=OR). <br/>Can have two words or more between two commas (=AND). <br/>Can be a hastag (don't forget the #)
		 
		  <br /><br />
                   <label for="pass">Download also user information :</label>
                   <input type="checkbox" name="user_info" value="user_info" <?php echo $user_info." ".$disabled; ?>>

		  
		   <br /><br />
		<?php if(isATaskRunning()) { ?>
		<input type="submit" value="Stop task" onclick="window.location.href='task.php?stop=1'">
		<input type="submit" value="Pause task" onclick="window.location.href='task.php?pause=1'" <?php if(isATaskInPause()) echo "disabled"; ?>>
		<input type="submit" value="Resume task" onclick="window.location.href='task.php?resume=1'" <?php if(!isATaskInPause()) echo "disabled"; ?>>

			

		<?php }
		else { ?>
		   <input type="submit" name="create" value="Create task" id="pass" />
		<?php } ?>	   
</p>

	<?php if(!isATaskRunning()) echo '</form>'; else { ?>
<h2><a href = "workfile">Results file </a> (Currently : <p style="display:inline" id="results_nb">0</p> results/tweets)</h2>
<h2>Errors/warnings</h2>
<textarea style="width:800px;height:200px" id="error_textarea"></textarea>
<?php } ?>
</div>
</body>

