<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=tweetbase;charset=utf8', 'tweetbase', 'TWEETBASE');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

//Add a task in the database
function addTask($keywords, $user_id, $user_info)
{
	global $bdd;

	$req = $bdd->prepare('INSERT INTO task(task_start_datetime, task_end_datetime, keywords, user_id, end_datetime, start_datetime, user_information, state) VALUES(NOW(), :task_end_datetime, :keywords, :user_id, :end_datetime, NOW(), :user_information, 1)');
	if(!$req)
	{
		//echo "\nPDO::errorInfo():\n";
   		 //print_r($req->errorInfo());
	}
	$req->execute(array(
		'task_end_datetime' => 0, 
		'keywords' => $keywords, 
		'user_id' => $user_id, 
		'end_datetime' => 0, 
		'user_information' => $user_info
    	));
	//print_r($bdd->errorInfo()); 
	echo 'Task created !<br/>';
}

//Know if a task is running
function isATaskRunning()
{
	$result = $bdd->query("SELECT COUNT(*) FROM task WHERE state = 1");
	if($result->fetchColumn()>0) return true;
	else return false;
}

?>
