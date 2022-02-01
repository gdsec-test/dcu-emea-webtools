<html>
    <head>
        <link rel="stylesheet" href="../common/ig_main.css" type="text/css">
    </head>
    <body>
        <h3>URL De-Sanitizer</h3>
        <form action="index.php" method="post">
            <label for="url">URL</label>
            <textarea id="url" name="url" rows="10" cols="50"></textarea></p>
            <p><input type="submit" /></p>
        </form>
    <?php
        //INPUT
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = sanitize($_POST["url"]);
            $arr = explode("\r\n", $result);
            for ($i = 0; $i < count($arr); $i++) {
                $host = parse_url($arr[$i], PHP_URL_HOST);
                $ip = gethostbyname($host);
                $ch = curl_init($arr[$i]);
                curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                echo "Analysis for: ". $arr[$i] ."<br />";
                echo "<b>".$host . "</b> is hosted at <b>". $ip ."</b><br />";
                echo "Return code for the provided URL: <b>". $http_code ."</b><br /><br />";
            }
        }

        function sanitize($data) {
            $data = trim($data, "\n\r\t\v\x00");
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $data = str_ireplace("hxxp", "http", $data);
            $data = str_replace("]", "" , $data);
            $data = str_replace("[", "", $data);
            return $data;
        }
    ?>
    </body>
</html>
