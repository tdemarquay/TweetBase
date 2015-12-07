

<?php
include ('bdd.php');
include('functions.php');

$error = "";

if(isset($_GET['delete']))
{
	deleteTask($_GET['delete']);
	$error = "Task deleted";
}
if(isset($_GET['purge']))
{
        purge();
        $error = "All data deleted";
}

if(isset($_GET['csv'])) 
{
	if(file_exists("mydata.csv")) unlink("mydata.csv");
	generateCSV($_GET['csv']);
	header('Location: mydata.csv');
}
?>
<head>
<title> List past tasks</title>

</head>
<body>
<a href="task.php">Create/current task</a><br/><br/>
<h1>List tasks</h1>
<?php echo "<br/>".$error."<br/><br/>"; ?>
<div style="text-align:center">
<table style="width:90%;border: 1px solid black;margin:auto"> 
<tr>
<td>Task ID</td>
<td>State</td>
<td>Keywords</td>
<td>Users</td>
<td>Nb results</td>
<td>User info</td>
<td>Start</td>
<td>End</td>
<td>Delete</td>
<td>Download</td>
</tr>
<?php
	$tasks = getTasks();
	//Browse all tasks
	foreach($tasks as $task)
	{
		echo "<tr>";
		echo "<td>".$task['task_id']."</td>";
		//Task column
		if($task['state']==0)echo "<td>Stopped</td>";
		else if($task['state']==1)echo "<td>Current</td>";
		else echo "<td>Paused</td>";
		
		echo "<td>".$task['keywords']."</td>";
		echo "<td>".$task['users']."</td>";
		echo "<td>".getNumberTweets($task['task_id'])."</td>";
		if($task['user_information']==0)echo "<td>No</td>";
		else echo "<td>Yes</td>";
		
		
		echo "<td>".$task['task_start_datetime']."</td>";
		echo "<td>".$task['task_end_datetime']."</td>";
		if($task['state']==0) echo "<td><input type='button' onclick=\"location.href='listTasks.php?delete=".$task['task_id']."';\" value=\"Delete\"></td>";
		else echo "Current task";
		if($task['state']==0)echo "<td><input type='button' onclick=\"location.href='listTasks.php?csv=".$task['task_id']."';\" value=\"CSV\"></td>";
		else echo "Current task";
		echo "</tr>";
	}
?>
</table>

<br/>
<td><input type='button' onclick="location.href='listTasks.php?purge=1';" value="Delete all data">

</div>
</body>
