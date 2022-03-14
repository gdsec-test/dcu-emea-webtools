<?php 
    $version = "1.0"
    /* URL-Checker 
    DO NOT any manual changes to this code. It will be vanished with next deployment.
    Codebase: https://github.com/gdcorp-infosec/dcu-emea-webtools
    Purpose: displays database records added by urlcheck for tracking and perform recheck */
?>
<html>
    <head>
        <link rel="stylesheet" href="../common/ig_main.css" type="text/css">
    </head>
    <body>
        <h1><a href="index.php">URL Tracking <?php echo $version ?></a></h1>
        <h3><a href="#malware">Malware</a> - <a href="#phishing">Phishing</a> - <a href="#copyright">Copyright</a></h3>
        <?php
            require_once("../common/urlhistory_connect.php");
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // for debugging purposes forward mysql-errors to php
            if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"])) {
                if ($_GET["action"] === "resolve") {
                    $url = $_GET["url"];
                    $category = $_GET["cat"];
                    $sql = "UPDATE `listing` SET `done` = 'true' WHERE `url` = '$url' AND `category` = '$category';";
                    $result = $connection->query($sql);
                    if ($result) {
                        echo "Entry has been set to done";
                    } else {
                        echo "Something went wrong with {$url}";
                    }
                }
            }
            $sql = "SELECT * FROM `listing` WHERE `category` = 'malware' AND `done` = 'false';";
            $result = $connection->query($sql);
            if ($result) { ?>
                <table id="malware">
                    <caption>Malware</caption>
                    <tr>
                        <th onclick="sortMalware(0)">Domain</th>
                        <th onclick="sortMalware(1)">IP</th>
                        <th onclick="sortMalware(2)">AS</th>
                        <th onclick="sortMalware(3)">Category</th>
                        <th onclick="sortMalware(4)">Last seen</th>
                        <th onclick="sortMalware(5)">HTTP Code</th>
                        <th onclick="sortMalware(6)">First seen</th>
                        <th onclick="sortMalware(7)">First IP</th>
                        <th onclick="sortMalware(8)">URL</th>
                    </tr>
                    <?php
                        while ($row = $result->fetch_assoc()) {
                            $url = base64_decode($row["url"]);
                            echo "<tr><td>{$row['lasthost']}</td><td>{$row['lastip']}</td><td>{$row['lastas']}</td><td>{$row['category']}</td><td>{$row['lasthit']}</td><td>{$row['rc']}</td><td>{$row['firsthit']}</td><td>{$row['firstip']}</td><td>$url</td><td><a href=\"../urlcheck/index.php?action=recheck&url={$row['url']}\">recheck</a></td><td><a href=\"index.php?action=resolve&cat={$row['category']}&url={$row['url']}\">resolve</a></td></tr>";
                        } ?>
                </table><?php
            } else {
                echo "Oops! Something went wrong with database.";
            }
            $sql = "SELECT * FROM `listing` WHERE `category` = 'phishing' AND `done` = 'false';";
            $result = $connection->query($sql);
            if ($result) { ?>
                <table id="phishing">
                    <caption>Phishing</caption>
                    <tr>
                        <th onclick="sortPhish(0)">Domain</th>
                        <th onclick="sortPhish(1)">IP</th>
                        <th onclick="sortPhish(2)">AS</th>
                        <th onclick="sortPhish(3)">Category</th>
                        <th onclick="sortPhish(4)">Last seen</th>
                        <th onclick="sortPhish(5)">HTTP Code</th>
                        <th onclick="sortPhish(6)">First seen</th>
                        <th onclick="sortPhish(7)">First IP</th>
                        <th onclick="sortPhish(8)">URL</th>
                     </tr>
                    <?php
                        while ($row = $result->fetch_assoc()) {
                            $url = base64_decode($row["url"]);
                            echo "<tr><td>{$row['lasthost']}</td><td>{$row['lastip']}</td><td>{$row['lastas']}</td><td>{$row['category']}</td><td>{$row['lasthit']}</td><td>{$row['rc']}</td><td>{$row['firsthit']}</td><td>{$row['firstip']}</td><td>$url</td><td><a href=\"../urlcheck/index.php?action=recheck&url={$row['url']}\">recheck</a></td><td><a href=\"index.php?action=resolve&cat={$row['category']}&url={$row['url']}\">resolve</a></td></tr>";
                        } ?>
                </table><?php
            } else {
                echo "Oops! Something went wrong with database.";
            }
            $sql = "SELECT * FROM `listing` WHERE `category` = 'copyright' AND `done` = 'false';";
            $result = $connection->query($sql);
            if ($result) { ?>
                <table id="copyright">
                    <caption>Copyright / Trademarks</caption>
                    <tr>
                        <th onclick="sortTrade(0)">Domain</th>
                        <th onclick="sortTrade(1)">IP</th>
                        <th onclick="sortTrade(2)">AS</th>
                        <th onclick="sortTrade(3)">Category</th>
                        <th onclick="sortTrade(4)">Last seen</th>
                        <th onclick="sortTrade(5)">HTTP Code</th>
                        <th onclick="sortTrade(6)">First seen</th>
                        <th onclick="sortTrade(7)">First IP</th>
                        <th onclick="sortTrade(8)">URL</th>
                     </tr>
                    <?php
                        while ($row = $result->fetch_assoc()) {
                            $url = base64_decode($row["url"]);
                            echo "<tr><td>{$row['lasthost']}</td><td>{$row['lastip']}</td><td>{$row['lastas']}</td><td>{$row['category']}</td><td>{$row['lasthit']}</td><td>{$row['rc']}</td><td>{$row['firsthit']}</td><td>{$row['firstip']}</td><td>$url</td><td><a href=\"../urlcheck/index.php?action=recheck&url={$row['url']}\">recheck</a></td><td><a href=\"index.php?action=resolve&cat={$row['category']}&url={$row['url']}\">resolve</a></td></tr>";
                        } ?>
                </table><?php
            } else {
                echo "Oops! Something went wrong with database.";
            }
            mysqli_close($connection);
        ?>
        <script>
            function sortMalware(n) {
              var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
              table = document.getElementById("malware");
              switching = true;
              // Set the sorting direction to ascending:
              dir = "asc";
              /* Make a loop that will continue until
              no switching has been done: */
              while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /* Loop through all table rows (except the
                first, which contains table headers): */
                for (i = 1; i < (rows.length - 1); i++) {
                  // Start by saying there should be no switching:
                  shouldSwitch = false;
                  /* Get the two elements you want to compare,
                  one from current row and one from the next: */
                  x = rows[i].getElementsByTagName("TD")[n];
                  y = rows[i + 1].getElementsByTagName("TD")[n];
                  /* Check if the two rows should switch place,
                  based on the direction, asc or desc: */
                  if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                      // If so, mark as a switch and break the loop:
                      shouldSwitch = true;
                      break;
                    }
                  } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                      // If so, mark as a switch and break the loop:
                      shouldSwitch = true;
                      break;
                    }
                  }
                }
                if (shouldSwitch) {
                  /* If a switch has been marked, make the switch
                  and mark that a switch has been done: */
                  rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                  switching = true;
                  // Each time a switch is done, increase this count by 1:
                  switchcount ++;
                } else {
                  /* If no switching has been done AND the direction is "asc",
                  set the direction to "desc" and run the while loop again. */
                  if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                  }
                }
              }
            }
            function sortPhish(n) {
              var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
              table = document.getElementById("phishing");
              switching = true;
              // Set the sorting direction to ascending:
              dir = "asc";
              /* Make a loop that will continue until
              no switching has been done: */
              while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /* Loop through all table rows (except the
                first, which contains table headers): */
                for (i = 1; i < (rows.length - 1); i++) {
                  // Start by saying there should be no switching:
                  shouldSwitch = false;
                  /* Get the two elements you want to compare,
                  one from current row and one from the next: */
                  x = rows[i].getElementsByTagName("TD")[n];
                  y = rows[i + 1].getElementsByTagName("TD")[n];
                  /* Check if the two rows should switch place,
                  based on the direction, asc or desc: */
                  if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                      // If so, mark as a switch and break the loop:
                      shouldSwitch = true;
                      break;
                    }
                  } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                      // If so, mark as a switch and break the loop:
                      shouldSwitch = true;
                      break;
                    }
                  }
                }
                if (shouldSwitch) {
                  /* If a switch has been marked, make the switch
                  and mark that a switch has been done: */
                  rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                  switching = true;
                  // Each time a switch is done, increase this count by 1:
                  switchcount ++;
                } else {
                  /* If no switching has been done AND the direction is "asc",
                  set the direction to "desc" and run the while loop again. */
                  if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                  }
                }
              }
            }
            function sortTrade(n) {
              var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
              table = document.getElementById("copyright");
              switching = true;
              // Set the sorting direction to ascending:
              dir = "asc";
              /* Make a loop that will continue until
              no switching has been done: */
              while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /* Loop through all table rows (except the
                first, which contains table headers): */
                for (i = 1; i < (rows.length - 1); i++) {
                  // Start by saying there should be no switching:
                  shouldSwitch = false;
                  /* Get the two elements you want to compare,
                  one from current row and one from the next: */
                  x = rows[i].getElementsByTagName("TD")[n];
                  y = rows[i + 1].getElementsByTagName("TD")[n];
                  /* Check if the two rows should switch place,
                  based on the direction, asc or desc: */
                  if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                      // If so, mark as a switch and break the loop:
                      shouldSwitch = true;
                      break;
                    }
                  } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                      // If so, mark as a switch and break the loop:
                      shouldSwitch = true;
                      break;
                    }
                  }
                }
                if (shouldSwitch) {
                  /* If a switch has been marked, make the switch
                  and mark that a switch has been done: */
                  rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                  switching = true;
                  // Each time a switch is done, increase this count by 1:
                  switchcount ++;
                } else {
                  /* If no switching has been done AND the direction is "asc",
                  set the direction to "desc" and run the while loop again. */
                  if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                  }
                }
              }
            }
        </script>
    </body>
</html>
