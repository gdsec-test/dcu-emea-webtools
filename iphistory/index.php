<html>
<head>
    <link rel="stylesheet" href="../common/ig_main.css" type="text/css">
</head>
<body>
<form action="index.php" method="post">
 <p>IP: <input type="text" name="ip" /></p>
 <p><input type="submit" /></p>
</form>
<?php
require_once('../common/dbconnect.php');

//INPUT
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $ip = test_input($_POST["ip"]);
}
else {
  die("You don't have to be here!");
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

echo "<h3>Routing history for ip: " . $ip."</h3>";

$sql = "select t.name as Action, r.entry_date as Timestamp, r.target_ip_address as IP, c.contract_id as Contract, c.company_id as Company from router.ip_route_log as r left join router.log_type as t on r.log_type_id = t.log_type_id left join ip_entity.ip as i on r.ip_address = i.ip_address left join ip_entity.ip_contract_binding as c on i.ip_id = c.ip_id where r.ip_address = '{$ip}' and r.entry_date > c.assign_date_time and c.release_date_time IS NULL order by r.entry_date desc;";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo $row["Timestamp"]. " - " . $row["Action"]. " - " . $row["IP"]. " - contract: <a href=\"https://tools.privatnetz.org/infotool/contract/" . $row["Company"] . "/" . $row["Contract"] .  "\" target=\"_blank\" rel=\"noopener noreferrer\">" . $row["Contract"] . "</a><br>";
  }
} else {
  echo "0 results";
}
$connection->close();
?>
</body>
</html>
