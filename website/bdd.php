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

	try
	{
        	$bdd = new PDO('mysql:host=localhost;dbname=tweetbase;charset=utf8', 'tweetbase', 'TWEETBASE');
	}
	catch (Exception $e)
	{
        	die('Erreur : ' . $e->getMessage());
	}


	$req = $bdd->prepare('INSERT INTO task(task_start_datetime, task_end_datetime, keywords, user_id, end_datetime, start_datetime, user_information) VALUES(NOW(), :task_end_datetime, :keywords, :user_id, :end_datetime, NOW(), :user_information)');
	if(!$req)
	{
		echo "\nPDO::errorInfo():\n";
   		 print_r($req->errorInfo());
	}
	$req->execute(array(
		'task_end_datetime' => 0, 
		'keywords' => $keywords, 
		'user_id' => $user_id, 
		'end_datetime' => 0, 
		'user_information' => $user_info
    	));
	print_r($bdd->errorInfo()); 
	echo 'Task created !';
}



?>
