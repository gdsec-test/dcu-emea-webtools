<html>
<head>
  <link rel="stylesheet" href="https://intern.privatnetz.org/generic/css/ig_main.css" type="text/css">
</head>
<body>
<h3>Closed Abuse Issues</h3>
<form action="index.php" method="get">
 <p>Abuse ID: <input type="text" name="aid" /></p>
 <input type="hidden" name="action" value="reopen" />
 <p><input type="submit" /></p>
</form>
<?php
require_once('../common/dbconnect.php');
function test_input($data) {
	$data = trim($data);
 	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	if (is_numeric($data)) {
		return $data;
	}
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$aid = test_input($_GET["aid"]);
}
else {
	die("You don't have to be here!");
}

if ($_GET["action"] == "reopen") {
        $sql = "UPDATE abuse.abuse SET abuse_status_id = 2 WHERE abuse_id = '" . ${aid} . "';";
        $result = $connection->query($sql);
	$connection->close();
}

$sql = "SELECT a.abuse_id AS aid, a.contract_id as cid FROM abuse.abuse AS a WHERE abuse_status_id = 6 AND create_date BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW() ORDER BY create_date DESC;";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
	// output data of each row
	echo "<b>Abuse ID - Contract ID</b><br />";
	while($row = $result->fetch_assoc()) {
		echo $row["aid"]. " - " . $row["cid"] . " - <a href=\"index.php?action=reopen&aid=" . $row["aid"] . "\">reopen</a><br />";
	}
} else {
	echo "0 results in Whitelist :)";
}
$connection->close();
?>
</body>
</html>
