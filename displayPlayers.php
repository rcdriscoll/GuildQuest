<html>
<head>
<title>Players</title>
<link rel="stylesheet" href="tables.css">
</head>
<body>
<center>
<h2>Display Players</h2>

<?php
	require "guildQuestConfig.php";
	$mysqli = new mysqli($host, $user, $password, $dbname, $port);

        if ($mysqli->conect_errno)
        {
                printf("Connect failed: %s\n", $mysqli->connect_error);
                exit();
        }


	// run query to select all from PLAYER table
	$result = $mysqli->query("SELECT * FROM PLAYER;");
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
                        echo "</tr>";
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
