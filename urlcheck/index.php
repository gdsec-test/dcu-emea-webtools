<html>
    <head>
        <link rel="stylesheet" href="../common/ig_main.css" type="text/css">
    </head>
    <body>
        <form action="index.php" method="post">
            <p>URL: <input type="textarea" name="url" /></p>
            <p><input type="submit" /></p>
        </form>
    <?php
        //INPUT
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = sanitize($_POST["url"]);
            $host = parse_url($result, PHP_URL_HOST));
            $ip = gethostbyname($host);
            $ch = curl_init($result);
            curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            echo "Analysis for: ". $result ."<br />";
            echo $host . "is hosted at ". $ip " - Return code for the provided URL: ". $http_code;
        }

        function sanitize($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $data = str_replace(['hxxp','hXXp','[',']'], ['http', 'http'], $data);
            return $data;
        }
    ?>
    </body>
</html>
