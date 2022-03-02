<?php 
    $version = "2.0"
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
            <p><label for="useragent">Multiple User Agents: </label>
            <input type="checkbox" name="useragent" value="mobile"></p>
            <p><input type="submit" /></p>
        </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = sanitize((string)$_POST["url"]); // cast $_POST to string, to avoid an array injection
            $arr = explode("\r\n", $result);
            $proxy = "http://proxy.hosteurope.de:3128";
            if (!empty($_POST["useragent"])) {
                $mobile = true;
                $useragent = array("Android" => "Mozilla/5.0 (Linux; Android 12; SAMSUNG SM-G998B) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/16.0 Chrome/92.0.4515.166 Mobile Safari/537.36",
                    "iOS" => "Mozilla/5.0 (iPhone; CPU iPhone OS 15_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1",
                    "Windows Edge" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36 Edg/98.0.1108.62",
                    "Windows Firefox" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:97.0) Gecko/20100101 Firefox/97.0",
                    "Windows Chrome" => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36"
                );
            }
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
                if(!$mobile) {
                    $ch = curl_init($arr[$i]);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_PROXY, $proxy);
                    curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                } else {
                    foreach ($useragent as $os => $agent){
                        $ch = curl_init($arr[$i]);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_PROXY, $proxy);
                        curl_setopt($ch, CURLOPT_USERAGENT, $useragent[$agent]);
                        curl_exec($ch);
                        $http_code[] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);
                    }
                }
                if (is_string($host)) {
                    echo "<br />Analysis for: ". $arr[$i] ."<br />";
                    echo "<b>".$host . "</b> is hosted at <b>". $ip ."</b> => (". $as . ") => (" . $abusec[0]['entries'][0] . ")<br />";
                    if ($mobile) {
                        $n = 0;
                        foreach ($useragent as $os => $agent){
                            echo "Return code for the provided URL: <b>". $http_code[$n] ."</b> - with user agent: <b>". array_keys($useragent)[$n] ."</b><br />";
                            $n++;
                        }
                    } else {
                        echo "Return code for the provided URL: <b>". $http_code ."</b><br />";
                    }
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

