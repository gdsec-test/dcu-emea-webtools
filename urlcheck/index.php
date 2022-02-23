<?php 
    $version = "1.4"
    /* URL-Checker 
    DO NOT any manual changes to this code. It will be vanished with next deployment.
    Codebase: https://github.com/gdcorp-infosec/dcu-emea-webtools
    Purpose: Accepts one or more URLs or domains in various sanitized forms,
        returns the A-Record for the domain part,
        AS number for the ip address (based on geo-ip),
        abuse contact for ip (based on abusix),
        http status code for given URL */
?>
<html>
    <head>
        <link rel="stylesheet" href="../common/ig_main.css" type="text/css">
    </head>
    <body>
        <h3>URL Checker <?php echo $version ?></h3>
        <form action="index.php" method="post">
            <label for="url">URL</label>
            <textarea id="url" name="url" rows="10" cols="50"></textarea></p>
            <p><input type="submit" /></p>
        </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = sanitize((string)$_POST["url"]); // cast $_POST to string, to avoid an array injection
            $arr = explode("\r\n", $result);
            for ($i = 0; $i < count($arr); $i++) {
                $host = parse_url($arr[$i], PHP_URL_HOST);
                if (!is_string($host)) {
                    $scheme = "http://";
                    $scheme .= $arr[$i];
                    $host = parse_url($scheme, PHP_URL_HOST);
                }
                $ip = gethostbyname($host);
                if (function_exists("geoip_asnum_by_name")) {
                    $as = geoip_asnum_by_name($ip);
                } else {
                    $as = '';
                }
                $oct = explode(".", $ip);
                $revarr = array($oct[3], $oct[2], $oct[1], $oct[0]);
                $revip = implode(".", $revarr);
                $abusec = dns_get_record("$revip.abuse-contacts.abusix.zone.", DNS_TXT);
                $ch = curl_init($arr[$i]);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if (is_string($host)) {
                    echo "Analysis for: ". $arr[$i] ."<br />";
                    echo "<b>".$host . "</b> is hosted at <b>". $ip ."</b> => (". $as . ") => (" . $abusec[0]['entries'][0] . ")<br />";
                    echo "Return code for the provided URL: <b>". $http_code ."</b><br /><br />";
                } else {
                    echo $arr[$i] . "has been skipped, as this seems not to be a valid URL!";
                }
            }
        }

        function sanitize($data) {
            $data = trim($data, " \n\r\t\v\x00");
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $data = str_ireplace(array('hxxp', '[dot]', '[', ']', ' '), array('http', '.', '', '', ''), $data);
            return $data;
        }
    ?>
    </body>
</html>

