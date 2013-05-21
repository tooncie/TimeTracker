<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php 

	$week_start = new DateTime("2013-04-08");
	$week_end = new DateTime("2013-04-08");
	$week_end->modify("+7 days");	
	
	echo "<h1> Your Hours for the Week of " . $week_start->format('m-d-Y') . " to " . $week_end->format('m-d-Y') . "</h1>";
	
	// MySQL query for hours
	$db = new mysqli('localhost', 'hoursorama', 'hoursorama123', 'hours');
	if (mysqli_connect_errno()){
		echo 'Error: Could not connect to database.  Please try again later.';
		exit;
	}
	$query = "select * from hours where date between '" . $week_start->format('Y-m-d') . "' and '" . $week_end->format('Y-m-d') . 
				"' order by date asc";
	$result = $db->query($query);
	//echo $query;
	
	// Array of this week's hours by date
	$hours_worked = array();
	while($row = $result->fetch_assoc()){
		$hours_worked[$row['date']] = $row['time'];
	}
		
	// Print out Date Row
	$current_day = clone $week_start;
	echo "<table><tr>";
	for ($day = 0; $day < 7; $day++){
		$current_day = $current_day->add(new DateInterval('P1D'));
		echo "<td>" . $current_day->format('m-d') . "</td>";
	}
	echo "</tr>";
	
	// Print out day of week row
	$days_of_week= array("MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN");
	echo "<tr>";
	for ($day = 0; $day < 7; $day++){
		echo "<td> $days_of_week[$day] </td>";
	}
	echo "</tr>";
	
	// Print out hours row
	$current_day = clone $week_start;
	echo "<form action='index.php' method='post'>";
	echo "<tr>";
	for ($day = 0; $day < 7; $day++){
		$current_day = $current_day->add(new DateInterval('P1D'));
		
		echo "<td><input value=";
		if (isset($hours_worked[$current_day->format('Y-m-d')]))
			echo $hours_worked[$current_day->format('Y-m-d')];			
		else
			echo "0";
		echo " size='1'></input></td>";
	}
	echo "</tr></table>";
	
	echo "<button type='submit'>Submit</button>";
	echo "</form>";
	
	/*
	if(sizeof($_POST) == 0){
		echo "Size of POST 0";
	}
	else{
		echo "Size of POST NOT 0";
	}
	*/
	
	$result->free();
	$db->close();
?>
</body>
</html>