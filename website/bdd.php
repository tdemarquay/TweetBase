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

function getTaskUserId($id)
{
	global $bdd;
        $result = $bdd->query("SELECT user_id FROM task WHERE task_id = ".$id);
        return $result->fetch()['user_id'];
}

function getTaskUserInfo($id)
{
	global $bdd;
        $result = $bdd->query("SELECT user_information FROM task WHERE task_id = ".$id);
        if($result->fetch()['user_information'] == 1) return true; else return false;
}

function getTaskFuture($id)
{
	global $bdd;
        $result = $bdd->query("SELECT end_datetime FROM task WHERE task_id = ".$id);
    	if($result->fetch()['end_datetime'] == "0000-00-00 00:00:00") return true; else return false;
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

?>

