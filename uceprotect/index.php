<html>
<head>
    <?php include "../common/gdstyles.html"; ?>
</head>
<body>
<?php include "../common/gdicon.html"; ?>
<form action="index.php" method="post">
 <p>Suche nach IP: <input type="text" name="ip" /></p>
 <p><input type="submit" /></p>
</form>
<?php
require_once('../common/uceconnect.php');
require_once('chart.php');

//INPUT
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ip = test_input($_POST["ip"]);
}
else {
    echo "<h2>Current uceprotect listings</h2>";
    $data = [];
    $sql = "SELECT date, COUNT(*) AS data FROM `uceprotect`.`archive` GROUP BY YEAR(date), MONTH(date), DAY(date);";
    $result = $connection->query($sql);
    while($row = $result->fetch_assoc()) {
        array_push($data, $row['data']);
    }
    $chart = new Chart();
    $chart->setPixelSize(600, 100, 2);
    $chart->setMinMaxY(0, max($data));
    $chart->setMinMaxX(0,365,12);
    $errorMessage = $chart->addNewLine(0, 255, 0);
    foreach ($data as $i=>$value) {
        $errorMessage = $chart->setPoint($i, $value, '');
    }
    $chart->show(5);
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

$sql = $connection->prepare("SELECT address, date FROM uceprotect.archive WHERE address = '?' ORDER BY date DESC;");
$sql->bind_param("s", $ip);
$sql->execute();
$result = $sql->get_result();

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
