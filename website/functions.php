<?php
include_once('bdd.php');
function readFileAndSave($user_info)
{
	if(file_exists("workfile"))
	{

		$file = fopen("workfile", "r");

		while(!feof($file))
		{
			$line = fgets($file, 100024);
			//We check if the line is empty
			if(!empty($line))
			{
				$line = trim(preg_replace('/\s+/', ' ', $line));
				$json = json_decode($line,true);
				$user=saveUser($json['user']); 
				saveTweet($json, getCurrentTaskId());
			}
		}
	}
}

?>
