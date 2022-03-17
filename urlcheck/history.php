<?php
    require_once("../common/urlhistory_connect.php");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // for debugging purposes forward mysql-errors to php
    $result = sanitize((string)$_POST["url"]); // cast $_POST to string, to avoid an array injection
    $arr = explode("\r\n", $result);
    $proxy = "http://proxy.hosteurope.de:3128";
    $module = $_POST["module"];
    for ($i = 0; $i < count($arr); $i++) {
        $host = parse_url($arr[$i], PHP_URL_HOST);
        if (!is_string($host)) {
            $scheme = "http://";
            $scheme .= $arr[$i];
            $host = parse_url($scheme, PHP_URL_HOST);
        }
        $ip = gethostbyname($host);
        $reverse = gethostbyaddr($ip);
        if (function_exists("geoip_asnum_by_name")) {
            $as = geoip_asnum_by_name($ip);
            $asn = explode(" ", $as);
            $asn = (int)str_replace("AS", "", $asn[0]);
        } else {
            $asn = 0;
        }
        $oct = explode(".", $ip);
        $revarr = array($oct[3], $oct[2], $oct[1], $oct[0]);
        $revip = implode(".", $revarr);
        $abusec = dns_get_record("$revip.abuse-contacts.abusix.zone.", DNS_TXT);
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $ch = curl_init($arr[$i]);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (is_string($host)) {
            $url = base64_encode($arr[$i]);
            $sql = "INSERT INTO `listing` (`firstip`, `firstas`, `firsthost`, `lastip`, `lastas`, `lasthost`, `category`, `url`, `domain`, `rc`, `firsthit`, `lasthit`) VALUES ('$ip', '$asn', '$host', '$ip', '$asn', '$host', '$module', '$url', '$reverse', '$http_code', DATE_FORMAT(NOW(), \"%Y-%m-%d %H:%i\"), DATE_FORMAT(NOW(), \"%Y-%m-%d %H:%i\")) ON DUPLICATE KEY UPDATE `lastip`=VALUES(lastip), `lastas`=VALUES(lastas), `lasthost`=VALUES(lasthost), `rc`=VALUES(rc), `lasthit`=VALUES(lasthit), `done`='false';";
            if ($connection->query($sql)) {
                echo "{$host} has been added to the database!\r\n";
            } else {
                echo "Oops! Something went wrong with {$host}";
            }
        }
    }
    mysqli_close($connection);
?>
    <p>
    <form id="1" action="index.php" method="post">
        <label for="url">URL</label>
        <textarea id="url" name="url" rows="10" cols="50"></textarea></p>
        <p><label for="useragent">Multiple User Agents: </label>
        <input type="checkbox" name="useragent" value="mobile"></p>
        <p><input type="submit" name="analyze" value="analyze" /></p>
    </form>
    </p>
