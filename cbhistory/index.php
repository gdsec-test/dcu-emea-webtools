<html>
    <head>
        <?php include "../common/gdstyles.html"; ?>
    </head>
    <body>
        <?php
            include "../common/gdicon.html";
            require_once('../common/uceconnect.php');
            require_once('chart.php');

            echo "<h2>CERTBund whole year</h2>";

            // initial all needed arrays
            $bluekeep = [];
            $cldap = [];
            $elasticsearch = [];
            $emotet = [];
            $ipp = [];
            $malware = [];
            $memcached = [];
            $mongodb = [];
            $mssql = [];
            $netbios = [];
            $opendns = [];
            $portmapper = [];
            $redis = [];
            $snmp = [];
            $ssdp = [];
            $telnet = [];
            $bluekeep_data = [];
            $cldap_data = [];
            $elastic_data = [];
            $emotet_data = [];
            $ipp_data = [];
            $malware_data = [];
            $memcached_data = [];
            $mongodb_data = [];
            $mssql_data = [];
            $netbios_data = [];
            $opendns_data = [];
            $portmapper_data = [];
            $redis_data = [];
            $snmp_data = [];
            $ssdp_data = [];
            $telnet_data = [];

            $sql = "SELECT COUNT(*) AS hits, module, YEAR(date) AS y, MONTH(date) AS m FROM `certbund`.`archive` GROUP BY y, m, module ORDER BY module, m ASC;";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    switch($row['module']) {
                        case "bluekeep":
                            $bluekeep += array("{$row['y']}-{$row['m']}" => $row['hits']);
                            array_push($bluekeep_data, $row['hits']);
                            break;
                        case "cldap":
                            $cldap += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($cldap_data, $row['hits']);
                            break;
                        case "elasticsearch":
                            $elasticsearch += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($elastic_data, $row['hits']);
                            break;
                        case "emotet":
                            $emotet += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($emotet_data, $row['hits']);
                            break;
                        case "ipp":
                            $ipp += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($ipp_data, $row['hits']);
                            break;
                        case "malware":
                            $malware += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($malware_data, $row['hits']);
                            break;
                        case "memcached":
                            $memcached += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($memcached_data, $row['hits']);
                            break;
                        case "mongodb":
                            $mongodb += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($mongodb_data, $row['hits']);
                            break;
                        case "mssql":
                            $mssql += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($mssql_data, $row['hits']);
                            break;
                        case "netbios":
                            $netbios += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($netbios_data, $row['hits']);
                            break;
                        case "opendns":
                            $opendns += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($opendns_data, $row['hits']);
                            break;
                        case "portmapper":
                            $portmapper += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($portmapper_data, $row['hits']);
                            break;
                        case "redis":
                            $redis += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($redis_data, $row['hits']);
                            break;
                        case "snmp":
                            $snmp += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($snmp_data, $row['hits']);
                            break;
                        case "ssdp":
                            $ssdp += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($ssdp_data, $row['hits']);
                            break;
                        case "telnet":
                            $telnet += array("{$row['y']}-{$row['m']}" => "{$row['hits']}");
                            array_push($telnet_data, $row['hits']);
                            break;
                    }
                }
            }

            // initialize all charts
            $bluekeep_chart = new Chart();
            $bluekeep_chart->setPixelSize(600, 100, 2);
            $bluekeep_chart->setMinMaxY(0, max($bluekeep_data));
            $bluekeep_chart->setMinMaxX(0,12,3);
            $errorMessage = $bluekeep_chart->addNewLine(0, 255, 0);
            foreach ($bluekeep_data as $i=>$value) {
                $errorMessage = $bluekeep_chart->setPoint($i, $value, '');
            }
            $cldap_chart = new Chart();
            $cldap_chart->setPixelSize(600, 100, 2);
            $cldap_chart->setMinMaxY(0, max($cldap_data));
            $cldap_chart->setMinMaxX(0,12,3);
            $errorMessage = $cldap_chart->addNewLine(0, 255, 0);
            foreach ($cldap_data as $i=>$value) {
                $errorMessage = $cldap_chart->setPoint($i, $value, '');
            }
            $elastic_chart = new Chart();
            $elastic_chart->setPixelSize(600, 100, 2);
            $elastic_chart->setMinMaxY(0, max($elastic_data));
            $elastic_chart->setMinMaxX(0,12,3);
            $errorMessage = $elastic_chart->addNewLine(0, 255, 0);
            foreach ($elastic_data as $i=>$value) {
                $errorMessage = $elastic_chart->setPoint($i, $value, '');
            }
            $emotet_chart = new Chart();
            $emotet_chart->setPixelSize(600, 100, 2);
            $emotet_chart->setMinMaxY(0, max($emotet_data));
            $emotet_chart->setMinMaxX(0,12,3);
            $errorMessage = $emotet_chart->addNewLine(0, 255, 0);
            foreach ($emotet_data as $i=>$value) {
                $errorMessage = $emotet_chart->setPoint($i, $value, '');
            }
            $ipp_chart = new Chart();
            $ipp_chart->setPixelSize(600, 100, 2);
            $ipp_chart->setMinMaxY(0, max($ipp_data));
            $ipp_chart->setMinMaxX(0,12,3);
            $errorMessage = $ipp_chart->addNewLine(0, 255, 0);
            foreach ($ipp_data as $i=>$value) {
                $errorMessage = $ipp_chart->setPoint($i, $value, '');
            }
            $malware_chart = new Chart();
            $malware_chart->setPixelSize(600, 100, 2);
            $malware_chart->setMinMaxY(0, max($malware_data));
            $malware_chart->setMinMaxX(0,12,3);
            $errorMessage = $malware_chart->addNewLine(0, 255, 0);
            foreach ($malware_data as $i=>$value) {
                $errorMessage = $malware_chart->setPoint($i, $value, '');
            }
            $memcached_chart = new Chart();
            $memcached_chart->setPixelSize(600, 100, 2);
            $memcached_chart->setMinMaxY(0, max($memcached_data));
            $memcached_chart->setMinMaxX(0,12,3);
            $errorMessage = $memcached_chart->addNewLine(0, 255, 0);
            foreach ($memcached_data as $i=>$value) {
                $errorMessage = $memcached_chart->setPoint($i, $value, '');
            }
            $mongodb_chart = new Chart();
            $mongodb_chart->setPixelSize(600, 100, 2);
            $mongodb_chart->setMinMaxY(0, max($mongodb_data));
            $mongodb_chart->setMinMaxX(0,12,3);
            $errorMessage = $mongodb_chart->addNewLine(0, 255, 0);
            foreach ($mongodb_data as $i=>$value) {
                $errorMessage = $mongodb_chart->setPoint($i, $value, '');
            }
            $mssql_chart = new Chart();
            $mssql_chart->setPixelSize(600, 100, 2);
            $mssql_chart->setMinMaxY(0, max($mssql_data));
            $mssql_chart->setMinMaxX(0,12,3);
            $errorMessage = $mssql_chart->addNewLine(0, 255, 0);
            foreach ($mssql_data as $i=>$value) {
                $errorMessage = $mssql_chart->setPoint($i, $value, '');
            }
            $netbios_chart = new Chart();
            $netbios_chart->setPixelSize(600, 100, 2);
            $netbios_chart->setMinMaxY(0, max($netbios_data));
            $netbios_chart->setMinMaxX(0,12,3);
            $errorMessage = $netbios_chart->addNewLine(0, 255, 0);
            foreach ($netbios_data as $i=>$value) {
                $errorMessage = $netbios_chart->setPoint($i, $value, '');
            }
            $opendns_chart = new Chart();
            $opendns_chart->setPixelSize(600, 100, 2);
            $opendns_chart->setMinMaxY(0, max($opendns_data));
            $opendns_chart->setMinMaxX(0,12,3);
            $errorMessage = $opendns_chart->addNewLine(0, 255, 0);
            foreach ($oendns_data as $i=>$value) {
                $errorMessage = $opendns_chart->setPoint($i, $value, '');
            }
            $portmapper_chart = new Chart();
            $portmapper_chart->setPixelSize(600, 100, 2);
            $portmapper_chart->setMinMaxY(0, max($portmapper_data));
            $portmapper_chart->setMinMaxX(0,12,3);
            $errorMessage = $portmapper_chart->addNewLine(0, 255, 0);
            foreach ($portmapper_data as $i=>$value) {
                $errorMessage = $portmapper_chart->setPoint($i, $value, '');
            }
            $redis_chart = new Chart();
            $redis_chart->setPixelSize(600, 100, 2);
            $redis_chart->setMinMaxY(0, max($redis_data));
            $redis_chart->setMinMaxX(0,12,3);
            $errorMessage = $redis_chart->addNewLine(0, 255, 0);
            foreach ($redis_data as $i=>$value) {
                $errorMessage = $redis_chart->setPoint($i, $value, '');
            }
            $snmp_chart = new Chart();
            $snmp_chart->setPixelSize(600, 100, 2);
            $snmp_chart->setMinMaxY(0, max($snmp_data));
            $snmp_chart->setMinMaxX(0,12,3);
            $errorMessage = $snmp_chart->addNewLine(0, 255, 0);
            foreach ($snmp_data as $i=>$value) {
                $errorMessage = $snmp_chart->setPoint($i, $value, '');
            }
            $ssdp_chart = new Chart();
            $ssdp_chart->setPixelSize(600, 100, 2);
            $ssdp_chart->setMinMaxY(0, max($ssdp_data));
            $ssdp_chart->setMinMaxX(0,12,3);
            $errorMessage = $ssdp_chart->addNewLine(0, 255, 0);
            foreach ($ssdp_data as $i=>$value) {
                $errorMessage = $ssdp_chart->setPoint($i, $value, '');
            }
            $telnet_chart = new Chart();
            $telnet_chart->setPixelSize(600, 100, 2);
            $telnet_chart->setMinMaxY(0, max($telnet_data));
            $telnet_chart->setMinMaxX(0,12,3);
            $errorMessage = $telnet_chart->addNewLine(0, 255, 0);
            foreach ($telnet_data as $i=>$value) {
                $errorMessage = $telnet_chart->setPoint($i, $value, '');
            }


            echo "<h4>Bluekeep</h4>";
            echo "<table><tr>";
            $keys = array_keys($bluekeep);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($bluekeep[$keys[$i]] > $bluekeep[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$bluekeep[$keys[$i]]}</td>";
                } elseif ($bluekeep[$keys[$i]] < $bluekeep[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$bluekeep[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$bluekeep[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $bluekeep_chart->show(5);

            echo "<h4>cLDAP</h4>";
            echo "<table><tr>";
            $keys = array_keys($cldap);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($cldap[$keys[$i]] > $cldap[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$cldap[$keys[$i]]}</td>";
                } elseif ($cldap[$keys[$i]] < $cldap[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$cldap[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$cldap[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $cldap_chart->show(5);

            echo "<h4>Elasticsearch</h4>";
            echo "<table><tr>";
            $keys = array_keys($elasticsearch);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($elasticsearch[$keys[$i]] > $elasticsearch[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$elasticsearch[$keys[$i]]}</td>";
                } elseif ($elasticsearch[$keys[$i]] < $elasticsearch[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$elasticsearch[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$elasticsearch[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $elastic_chart->show(5);

            echo "<h4>Emotet</h4>";
            echo "<table><tr>";
            $keys = array_keys($emotet);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($emotet[$keys[$i]] > $emotet[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$emotet[$keys[$i]]}</td>";
                } elseif ($emotet[$keys[$i]] < $emotet[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$emotet[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$emotet[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $emotet_chart->show(5);

            echo "<h4>IPP printer service</h4>";
            echo "<table><tr>";
            $keys = array_keys($ipp);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($ipp[$keys[$i]] > $ipp[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$ipp[$keys[$i]]}</td>";
                } elseif ($ipp[$keys[$i]] < $ipp[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$ipp[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$ipp[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $ipp_chart->show(5);

            echo "<h4>Malware</h4>";
            echo "<table><tr>";
            $keys = array_keys($malware);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($malware[$keys[$i]] > $malware[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$malware[$keys[$i]]}</td>";
                } elseif ($malware[$keys[$i]] < $malware[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$malware[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$malware[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $malware_chart->show(5);

            echo "<h4>Memcached</h4>";
            echo "<table><tr>";
            $keys = array_keys($memcached);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($memcached[$keys[$i]] > $memcached[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$memcached[$keys[$i]]}</td>";
                } elseif ($memcached[$keys[$i]] < $memcached[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$memcached[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$memcached[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $memcached_chart->show(5);

            echo "<h4>MongoDB</h4>";
            echo "<table><tr>";
            $keys = array_keys($mongodb);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($mongodb[$keys[$i]] > $mongodb[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$mongodb[$keys[$i]]}</td>";
                } elseif ($mongodb[$keys[$i]] < $mongodb[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$mongodb[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$mongodb[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $mongodb_chart->show(5);

            echo "<h4>MS-SQL</h4>";
            echo "<table><tr>";
            $keys = array_keys($mssql);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($mssql[$keys[$i]] > $mssql[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$mssql[$keys[$i]]}</td>";
                } elseif ($mssql[$keys[$i]] < $mssql[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$mssql[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$mssql[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $mssql_chart->show(5);

            echo "<h4>NetBIOS</h4>";
            echo "<table><tr>";
            $keys = array_keys($netbios);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($netbios[$keys[$i]] > $netbios[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$netbios[$keys[$i]]}</td>";
                } elseif ($netbios[$keys[$i]] < $netbios[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$netbios[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$netbios[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $netbios_chart->show(5);

            echo "<h4>OpenDNS</h4>";
            echo "<table><tr>";
            $keys = array_keys($opendns);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($opendns[$keys[$i]] > $opendns[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$opendns[$keys[$i]]}</td>";
                } elseif ($opendns[$keys[$i]] < $opendns[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$opendns[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$opendns[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $opendns_chart->show(5);

            echo "<h4>Portmapper</h4>";
            echo "<table><tr>";
            $keys = array_keys($portmapper);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($portmapper[$keys[$i]] > $portmapper[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$portmapper[$keys[$i]]}</td>";
                } elseif ($portmapper[$keys[$i]] < $portmapper[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$portmapper[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$portmapper[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $portmapper_chart->show(5);

            echo "<h4>Redis</h4>";
            echo "<table><tr>";
            $keys = array_keys($redis);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($redis[$keys[$i]] > $redis[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$redis[$keys[$i]]}</td>";
                } elseif ($redis[$keys[$i]] < $redis[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$redis[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$redis[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $redis_chart->show(5);

            echo "<h4>SNMP</h4>";
            echo "<table><tr>";
            $keys = array_keys($snmp);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($snmp[$keys[$i]] > $snmp[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$snmp[$keys[$i]]}</td>";
                } elseif ($snmp[$keys[$i]] < $snmp[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$snmp[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$snmp[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $snmp_chart->show(5);

            echo "<h4>SSDP</h4>";
            echo "<table><tr>";
            $keys = array_keys($ssdp);
            for ($i=0; $i<12; $i++){
                echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($ssdp[$keys[$i]] > $ssdp[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$ssdp[$keys[$i]]}</td>";
                } elseif ($ssdp[$keys[$i]] < $ssdp[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$ssdp[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$ssdp[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $ssdp_chart->show(5);

            echo "<h4>telnet</h4>";
            echo "<table><tr>";
            $keys = array_keys($telnet);
            for ($i=0; $i<12; $i++){
                 echo "<th>{$keys[$i]}</th>";
            }
            echo "</tr><tr>";
            for ($i=0; $i<12; $i++){
                if($telnet[$keys[$i]] > $telnet[$keys[$i-1]]){
                    echo "<td style=\"color:#ff0000\">{$telnet[$keys[$i]]}</td>";
                } elseif ($telnet[$keys[$i]] < $telnet[$keys[$i-1]]){
                    echo "<td style=\"color:#00ff00\">{$telnet[$keys[$i]]}</td>";
                } else {
                    echo "<td>{$telnet[$keys[$i]]}</td>";
                }
            }
            echo "</tr></table>";
            $telnet_chart->show(5);

            $connection->close();
        ?>
    </body>
</html>
