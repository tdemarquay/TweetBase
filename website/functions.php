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

			$line = trim(preg_replace('/\s+/', ' ', $line));
			$json = json_decode($line,true);
			if($user_info)$user=saveUser($json['user']); else $user=0;
			saveTweet($json, getCurrentTaskId(), $user);
		
		}
	}
}

?>
