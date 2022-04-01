<?php 
    $version = "3.1"
    /* URL-Checker 
    DO NOT any manual changes to this code. It will be vanished with next deployment.
    Codebase: https://github.com/gdcorp-infosec/dcu-emea-webtools
    Purpose: Accepts one or more URLs or domains in various sanitized forms,
        returns the A-Record for the domain part,
        AS number for the ip address (based on geo-ip),
        abuse contact for ip (based on abusix),
        http status code for given URL
    Update 3.0: supports saving records to database with various incidents */
?>
<html>
    <head>
        <link rel="stylesheet" href="../common/ig_main.css" type="text/css">
    </head>
    <body>
        <h3>URL Checker <?php echo $version ?></h3>
        <?php
            if ($_SERVER["REQUEST_METHOD"] === "GET"){
        ?>
                <form id="1" action="index.php" method="post">
                    <label for="url">URL</label>
                    <textarea id="url" name="url" rows="10" cols="50"><?php if ($_GET["action"] === "recheck" && isset($_GET["url"])) { echo base64_decode($_GET["url"]); } ?></textarea></p>
                    <p><label for="useragent">Multiple User Agents: </label>
                    <input type="checkbox" name="useragent" value="mobile"></p>
                    <p><input type="submit" name="analyze" value="analyze" /></p>
                </form>
        <?php
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["analyze"])) {
            include("analyze.php");
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["history"])) {
            include("history.php");
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

