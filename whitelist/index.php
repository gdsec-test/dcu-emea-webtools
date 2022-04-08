<head>
    <?php include "../common/gdstyles.html"; ?>
</head>
<body>
<?php 
    include "../common/gdicon.html";
    require_once('../common/dbconnect.php');
    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
      $cid = test_input($_GET["cid"]);
    }
    else {
      die("You don't have to be here!");
    }

    if ($_GET["action"] === "delete") {
            $sql = $connection->prepare("DELETE FROM abuse.whitelist_customer WHERE customer_data_id = ?;");
            $sql->bind_param("i", $cid);
            $sql->execute();
            $connection->close();
    }

    echo "<h3>Abuse Whitelist</h3>";

    $sql = "SELECT w.customer_data_id AS cid, c.category_name AS Category FROM abuse.whitelist_customer AS w JOIN abuse.category AS c ON w.category_id = c.category_id;";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
      echo "<b>Customer Data ID - Category</b><br />";
      while($row = $result->fetch_assoc()) {
        echo $row["cid"]. " - " . $row["Category"] . " - <a href=\"index.php?action=delete&cid=" . $row["cid"] . "\">delete</a><br />";
      }
    } else {
      echo "0 results in Whitelist :)";
    }
    $connection->close();
?>
