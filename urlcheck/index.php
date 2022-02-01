<html>
    <head>
        <link rel="stylesheet" href="../common/ig_main.css" type="text/css">
    </head>
    <body>
        <h3>URL De-Sanitizer</h3>
        <form action="index.php" method="post">
            <p>URL: <input type="textarea" name="url" rows="10" cols="50" /></p>
            <p><input type="submit" /></p>
        </form>
    <?php
        //INPUT
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = sanitize($_POST["url"]);
            $host = parse_url($result, PHP_URL_HOST);
            $ip = gethostbyname($host);
            $ch = curl_init($result);
            curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            echo "Analysis for: ". $result ."<br />";
            echo "<b>".$host . "</b> is hosted at <b>". $ip ."</b><br />";
            echo "Return code for the provided URL: <b>". $http_code ."</b>";
        }

        function sanitize($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $data = str_replace("hxxp", "http", $data);
            $data = str_replace("]", "" , $data);
            $data = str_replace("[", "", $data);
            return $data;
        }
    ?>
    </body>
</html>
