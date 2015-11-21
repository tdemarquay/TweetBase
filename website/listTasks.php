

<?php
include ('bdd.php');
include('functions.php');

$error = "";

if(isset($_GET['delete']))
{
	deleteTask($_GET['delete']);
	$error = "Task deleted";
}
?>
<head>
<title> List past tasks</title>

</head>
<body>
<a href="task.php">Create/current task</a><br/><br/>
<?php echo "<br/>".$error."<br/>"; ?>
<div style="text-align:center">
<table style="width:90%;border: 1px solid black;margin:auto"> 
<tr>
<td>Task ID</td>
<td>State</td>
<td>Keywords</td>
<td>Users</td>
<td>Nb results</td>
<td>User info</td>
<td>Prev start </td>
<td>Prev end</td>
<td>Real start</td>
<td>Real end</td>
<td>Delete</td>
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
		echo "<td>".$task['user_id']."</td>";
		echo "<td>".getNumberTweets($task['task_id'])."</td>";
		if($task['user_information']==0)echo "<td>No</td>";
		else echo "<td>Yes</td>";
		
		
		echo "<td>".$task['task_start_datetime']."</td>";
		echo "<td>".$task['task_end_datetime']."</td>";
		echo "<td>".$task['start_datetime']."</td>";
		echo "<td>".$task['end_datetime']."</td>";
		echo "<td><input type='button' onclick=\"location.href='listTasks.php?delete=".$task['task_id']."';\" value=\"Delete\"></td>";
		echo "</tr>";
	}
?>
</table>
</div>
</body>
