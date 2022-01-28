<html>
<head>
    <link rel="stylesheet" href="../common/ig_main.css" type="text/css">
</head>
<body>
<form action="index.php" method="post">
 <p>Suche nach IP: <input type="text" name="ip" /></p>
 <p><input type="submit" /></p>
</form>
<?php
require_once('../common/uceconnect.php');

//INPUT
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ip = test_input($_POST["ip"]);
}
else {
    echo "<h2>Current uceprotect listings</h2>";
    $sql = "SELECT address, host, hits, first, last FROM uceprotect.listing WHERE done = '0' ORDER BY hits DESC;";
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
    echo "<table><tr><td><b>IP</b></td><td><b>Hostname</b></td><td><b>Hits</b></td><td><b>First Hit</b></td><td><b>Last Hit</b></td></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["address"]. "</td><td>" . $row["host"]. "</td><td>" . $row["hits"]. "</td><td>" . $row["first"]. "</td><td>" . $row["last"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No Listings <3";
    }
}

// Test, if Input value is an IPv4 address
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  if (filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
    return $data;
  }
  else {
    die("$data is not a valid IPv4 address!");
  }
}

echo "<h3>UCE-Protect History for ip: " . $ip."</h3>";

$sql = "SELECT address, date FROM uceprotect.archive WHERE address = '{$ip}' ORDER BY date DESC;";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo $row["address"]. " - " . $row["date"]. "</a><br>";
  }
} else {
  echo "'{$ip}' has 0 results.";
}
$connection->close();
?>
</body>
</html>
