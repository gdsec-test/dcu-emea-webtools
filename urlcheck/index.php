<?php 
    $version = "4.0"
    /* URL-Checker 
    DO NOT any manual changes to this code. It will be vanished with next deployment.
    Codebase: https://github.com/gdcorp-infosec/dcu-emea-webtools
    Purpose: Accepts one or more URLs or domains in various sanitized forms,
        returns the A-Record for the domain part,
        AS number for the ip address (based on geo-ip),
        abuse contact for ip (based on abusix),
        http status code for given URL
    Update 3.0: supports saving records to database with various incidents
    Update 4.0: supports transmission to PhishStory API */
?>
<html>
    <head>
        <?php include "../common/gdstyles.html"; ?>
    </head>
    <body>
    <?php include "../common/gdicon.html"; ?>
        <h3>URL Checker <?php echo $version ?></h3>
        <?php
            if ($_SERVER["REQUEST_METHOD"] === "GET"){
                include "form.php";
            }
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["analyze"])) {
            include("analyze.php");
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["history"])) {
            include("history.php");
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sendtous"])) {
            include("sendtous.php");
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

