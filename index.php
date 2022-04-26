<html>
    <head>
        <?php include "common/gdstyles.html"; ?>
    </head>
    <body>
        <?php
            include "common/gdicon.html";
            // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // can be used for debugging purposes. Passes mysql errors towards php
            require_once("common/uceconnect.php");
            $certq1 = "SELECT COUNT(address) AS hits, module, max(last) AS last FROM certbund.listing WHERE done = '0' GROUP BY module ORDER BY module ASC;";
            $certResult1 = $connection->query($certq1);
            $uceQLastRun = "SELECT FROM_UNIXTIME(abused) AS last FROM uceprotect.listing ORDER BY abused DESC LIMIT 1;";
            $uceRLastRun = $connection->query($uceQLastRun);
            $uceQHits = "SELECT SUM(hits) AS hits FROM uceprotect.listing WHERE done = 0;";
            $uceRHits = $connection->query($uceQHits);
            $uceQHosts = "SELECT COUNT(address) AS hosts FROM uceprotect.listing WHERE done = 0;";
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
                <th><a href="#" onclick="hidedcu()">DCU internal</a></th>
            </tr>
            <tr>
                <td style="display: block" id="dcu">
                    <a href="abuse/index.php" target="_blank" rel="noopener">Re-Open closed abuse cases (restricted)</a><br />
                    <a href="iphistory/index.php" target="_blank" rel="noopener">Routing history for additional/net ips (restricted)</a><br />
                    <a href="uceprotect/index.php" target="_blank" rel="noopener">Overview of current UCEprotect listings (restricted)</a><br />
                    <a href="urlcheck/index.php" target="_blank" rel="noopener">URL De-Sanitizer/Checker</a><br />
                    <a href="urlhistory/index.php" target="_blank" rel="noopener">URL Tracker</a><br />
                    <a href="cbhistory/index.php" target="_blank" rel="noopener">CERT-Bund History Reports</a><br />
                    <a href="whitelist/index.php" target="_blank" rel="noopener">Abuse Whitelist S4Y (restricted)</a><br />
                </td>
            </tr>
            <tr>
                <td>
                    restricted: Requires membership in intergenia Active Directory with group "Auth Special DCU"
                </td>
            </tr>
            <tr>
                <th><a href="#" onclick="hides4u()">S4Y Tools</a></th>
            </tr>
            <tr>
                <td style="display: block" id="s4u">
                    <a href="https://tools.ig.mass.systems/abuse-manager/" target="_blank" rel="noopener">S4Y Abuse Manager</a><br />
                    <a href="https://tools.ig.mass.systems/infotool" target="_blank" rel="noopener">S4Y Infotool</a><br />
                    <a href="https://tools.ig.mass.systems/sm" target="_blank" rel="noopener">S4Y ServerManager</a><br />
                    <a href="https://tools.ig.mass.systems/ipm" target="_blank" rel="noopener">S4Y IP Manager</a><br />
                    <a href="https://tools.ig.mass.systems/blockingmanagement" target="_blank" rel="noopener">S4Y Blockingmanager</a><br />
                    <a href="https://otrs.privatnetz.org/otrs/index.pl" target="_blank" rel="noopener">OTRS Ticket System</a><br />
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <th>UCEprotect</th>
                <th>CERT-Bund</th>
            </tr>
            <tr>
                <td valign="top"><?php echo "hits/hosts: {$ucehits}/{$ucehosts}<br />Last run: {$ucelast}"; ?></td>
                <td>
                    <table>
                        <?php 
                            while($row = $certResult1->fetch_assoc()) {
                                echo "<tr><td>{$row['module']}: </td><td>{$row['last']} - </td><td>Current Count: {$row['hits']}</td></tr>";
                            }
                        ?>
                    </table>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            var dcuvisible = true;
            var s4uvisible = true;
            function hides4u() { 
                if (s4uvisible) {
                    document.getElementById('s4u').style.display = 'none';
                    s4uvisible = false;
                } else {
                    document.getElementById('s4u').style.display = 'block';
                    s4uvisible = true;
                }
            }
            function hidedcu() {
                if (dcuvisible) {
                    document.getElementById('dcu').style.display = 'none';
                    dcuvisible = false;
                } else {
                    document.getElementById('dcu').style.display = 'block';
                    dcuvisible = true;
                }
            }
        </script>
    </body>
</html>
