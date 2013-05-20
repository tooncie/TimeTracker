<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php 
	
	$week_start = new DateTime("2013-04-07");
	$week_end = new DateTime("2013-04-07");
	$week_end->modify("+7 days");	
	
	echo "<h1> Your Hours for the Week of " . $week_start->format('m-d-Y') . " to " . $week_end->format('m-d-Y') . "</h1>";
	
	$db = new mysqli('localhost', 'hoursorama', 'hoursorama123', 'hours');
	if (mysqli_connect_errno()){
		echo 'Error: Could not connect to database.  Please try again later.';
		exit;
	}
	
	$query = "select * from hours where date between '" . $week_start->format('Y-m-d') . "' and '" . $week_end->format('Y-m-d') . 
				"' order by date asc";
	echo $query;
	$result = $db->query($query);
	
	// Create table of this week's hours
	$hours_worked = array();
	while($row = $result->fetch_assoc()){
		$hours_worked[$row['date']] = $row['time'];
	}
	
	$current_day = clone $week_start;
	for ($day = 0; $day < 7; $day++){
		$current_day = $current_day->add(new DateInterval('P1D'));
		//$current_day = $week_start->modify('+1 day');
		if (isset($hours_worked[$current_day->format('Y-m-d')]))
		{
			echo $hours_worked[$current_day->format('Y-m-d')];
		}
		//echo $current_day->format('m-d-y');
	}
	
	//echo "week START:" . $week_start->format('m-d-y');
	

	$result->free();
	$db->close();
?>
</body>
</html>