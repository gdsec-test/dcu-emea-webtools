<html>
    <head>
        <link rel="stylesheet" href="common/ig_main.css" type="text/css">
    </head>
    <body>
        <?php
            // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // can be used for debugging purposes. Passes mysql errors towards php
            require_once("common/uceconnect.php");
            $certq1 = "SELECT module, max(FROM_UNIXTIME(abused)) AS last FROM certbund.listing GROUP BY module ORDER BY module ASC;";
            $certResult1 = $connection->query($certq1);
            $uceQLastRun = "SELECT FROM_UNIXTIME(abused) AS last FROM uceprotect.listing ORDER BY abused DESC LIMIT 1;";
            $uceRLastRun = $connection->query($uceQLastRun);
            $uceQHits = "SELECT SUM(hits) AS hits FROM uceprotect.listing WHERE done = 0;";
            $uceRHits = $connection->query($uceQHits);
            $uceQHosts = "SELECT COUNT(address) AS hosts FROM uceprotectlisting WHERE done = 0;";
            $uceRHosts = $connection->query($uceQHosts);
            $connection->close();
            while($row = $uceRHits->fetch_assoc()) {
                $ucehits = $row['hits'];
            }
            while($row = $uceRHosts->fetch_assoc()) {
                $ucehosts = $row['hosts'];
            }
            while($row = $uceRLastRun->fetch_assoc()) {
                $ucelast = $row['last'];
            }
        ?>
        <h1>DCU Dashboard</h1>
        <table>
            <tr>
                <th>Link list</th>
            </tr>
            <tr>
                <th>DCU internal</th>
            </tr>
            <tr>
                <td>
                    <a href="abuse/index.php" target="_new">Re-Open closed abuse cases (restricted)</a><br />
                    <a href="iphistory/index.php" target="_new">Routing history for additional/net ips (restricted)</a><br />
                    <a href="uceprotect/index.php" target="_new">Overview of current UCEprotect listings (restricted)</a><br />
                    <a href="urlcheck/index.php" target="_new">URL De-Sanitizer/Checker</a><br />
                    <a href="whitelist/index.php" target="_new">Abuse Whitelist S4Y (restricted)</a><br />
                </td>
            </tr>
            <tr>
                <td>
                    restricted: Requires membership in intergenia Active Directory with group "Auth Special DCU"
                </td>
            </tr>
            <tr>
                <th>S4Y Tools</th>
            </tr>
            <tr>
                <td>
                    <a href="https://tools.ig.mass.systems/infotool" target="_new">S4Y Infotool</a><br />
                    <a href="https://tools.ig.mass.systems/sm" target="_new">S4Y ServerManager</a><br />
                    <a href="https://tools.ig.mass.systems/ipm" target="_new">S4Y IP Manager</a><br />
                    <a href="https://tools.ig.mass.systems/blockingmanagement" target="_new">S4Y Blockingmanager</a><br />
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <th>UCEprotect</th>
                <th>CERT-Bund</th>
            </tr>
            <tr>
                <td><?php echo "hits/hosts: {$ucehits}/{$ucehosts}"; ?></td>
                <td>Last hits: </td>
            </tr>
            <tr>
                <td valign="top"><?php echo "Last run: {$ucelast}"; ?></td>
                <td>
                    <?php
                        while($row = $certResult1->fetch_assoc()) {
                            echo "{$row['module']}: {$row['last']}<br />";
                        }
                    ?>
                </td>
            </tr>
        </table>
    </body>
</html>
