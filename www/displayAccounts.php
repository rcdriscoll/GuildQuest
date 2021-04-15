<html>
<head>
<title>Member Accounts</title>
<link rel="stylesheet" href="tables.css">
<link rel="stylesheet" href="adminPages.css">
</head>

<body class="displayPage">
<center>
<h2>Display Accounts</h2>

<?php
	
	require "guildQuestConfig.php";

	$mysqli = new mysqli($host, $user, $password, $dbname, $port);

        if ($mysqli->connect_errno)
        {
                printf("Connect failed: %s\n", $mysqli->connect_error);
                exit();
        }



        // run query to select all from Account table
        $result = $mysqli->query("SELECT * FROM ACCOUNT;");
?>

<table class="displayTable">

<tr>

<?php
        // print headers
        while($head = $result->fetch_field())
        {
                echo "<TH>";
                echo "$head->name";
                echo "</TH>";
        }

        echo "</TR>";

	if ($result)
	{
		while($row=$result->fetch_row())
		{
			echo "<tr>";
			for($i = 0; $i < $result->field_count; $i++)
			{
				echo "<td> $row[$i] </td>";
			}
		}
	}

	$result->close();
	$mysqli->close();

?>
</table>

<br>
<br>
<a href = adminHome.php>Return to Admin Home Page</a>


</body>
</html>
