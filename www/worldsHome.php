<html>
<head>
<title>Worlds</title>
<link rel="stylesheet" href="tables.css">
<link rel="stylesheet" href="adminPages.css">
<link rel="stylesheet" href="playerHome.css">
</head>

<body class="displayPage">
<h2>Worlds</h2>
<?php
	
	require "guildQuestConfig.php";

	$mysqli = new mysqli($host, $user, $password, $dbname, $port);

        if ($mysqli->connect_errno)
        {
                printf("Connect failed: %s\n", $mysqli->connect_error);
                exit();
        }



	// run query to select active worlds
	$stmt = $mysqli->prepare("SELECT DISTINCT WorldName, MaxPlots, MaxPlayerCapacity, InitialPlotPrices, PvP FROM WORLD, PLAYER, ACCOUNT WHERE PLAYER.World = WORLD.WorldName AND PLAYER.Account = ACCOUNT.Email AND Username = ?;");

	$stmt->bind_param("s", $username);

	$username = $_GET['username'];

	$stmt->execute();
	$activeWorlds = $stmt->get_result();
	
	$stmt->close();

	
	// prepared statement for displaying unregistered worlds
	$stmt = $mysqli->prepare("SELECT WorldName, MaxPlots, MaxPlayerCapacity, InitialPlotPrices, PvP FROM WORLD WHERE WorldName NOT IN (SELECT WorldName FROM WORLD, PLAYER, ACCOUNT WHERE PLAYER.World = WORLD.WorldName AND PLAYER.Account = ACCOUNT.Email AND Username = ?);");

	$stmt->bind_param("s", $username);
	$stmt->execute();

	$unregisteredWorlds = $stmt->get_result();
	
	$stmt->close();

?>
<br>
<table class="displayTable">
<TR>
<TH></TH>
<?php
        // print headers
		if($head = $activeWorlds->fetch_fields())
			for($i = 0; $i < $activeWorlds->field_count; $i++)
			{
				$temp = $head[$i];
				echo "<TH>";
                echo " $temp->name";
                echo "</TH>";
			}
        echo "</TR>";

	if ($activeWorlds)
	{
		while($row=$activeWorlds->fetch_row())
		{	
			echo "<tr>
					<td>
						<form method=\"POST\" action=\"playerHome.php?username=" . $_GET["username"] . "&world=" . $row[0] . "\">
							<input type=\"hidden\" id=\"username\" name=\"username\" value=\"" . $_GET["username"] . "\">
							<input type=\"hidden\" id=\"worldName\" name=\"worldName\" value=\"" . $row[0] . "\">
							<input type=\"submit\" id=\"joinWorldButton\" value=\"Join\">
						</form>
					</td>";
			for($i = 0; $i < $activeWorlds->field_count; $i++)
			{

				echo "<td> $row[$i] </td>";
			}
			echo "</tr>";
		}
	}

	$activeWorlds->close();

?>

</table>
<br>
<br>
<br>
<h2>Unregistered Worlds</h2>
<br>
<table class="displayTable">

<TR>
<TH></TH>
<?php
        // print headers
        if($head = $unregisteredWorlds->fetch_fields())
			for($i = 0; $i < $unregisteredWorlds->field_count; $i++)
			{
				$temp = $head[$i];
				echo "<TH>";
				echo " $temp->name";
				echo "</TH>";
			}
		echo "</TR>";

	if ($unregisteredWorlds)
	{
		while($row=$unregisteredWorlds->fetch_row())
		{	
			echo "<tr>
					<td>
						<form method=\"POST\" action=\"newWorldLogin.php\">
							<input type=\"hidden\" id=\"username\" name=\"username\" value=\"" . $_GET["username"] . "\">
							<input type=\"hidden\" id=\"worldName\" name=\"worldName\" value=\"" . $row[0] . "\">
							<input type=\"submit\" id=\"joinWorldButton\" value=\"Add\">
						</form>
					</td>";
			for($i = 0; $i < $unregisteredWorlds->field_count; $i++)
			{

				echo "<td> $row[$i] </td>";
			}
			echo "</tr>";
		}
	}

	$unregisteredWorlds->close();
	$mysqli->close();

?>

</table>

<br>
<br>
<a href = index.php id="returnButton">Logout</a>
<br>
<br>

</body>
</html>
