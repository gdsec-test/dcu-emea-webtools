<?php
    $mailrcpt = $_POST['abusec'];
    $type = $_POST['reason'];
    $domain = trim($_POST['url']);
    $domarr = explode("\r\n", $domain);
    $header = array(
        'From' => 'abuse-outgoing@hosteurope.de',
        'Reply-To' => 'abuse-outgoing@hosteurope.de',
        'X-Mailer' => 'PHP/' . phpversion(),
        'User-Agent' => 'EmeaURLCheckService V4.0'
    );
    for ($i = 0; $i < count($domarr); $i++) {
        $host = parse_url($domarr[$i], PHP_URL_HOST);
        if (!is_string($host)) {
            $scheme = "http://";
            $scheme .= $domarr[$i];
            $host = parse_url($scheme, PHP_URL_HOST);
        }
        $ip = gethostbyname($host);
        $oct = explode(".", $ip);
        $revarr = array($oct[3], $oct[2], $oct[1], $oct[0]);
        $revip = implode(".", $revarr);
        $mailrcpt = dns_get_record("$revip.abuse-contacts.abusix.zone.", DNS_TXT)[0]['entries'][0];
        $subject = "Abuse report for {$domarr[$i]}";
        $body = "Hello,
            we've received following Abuse complaint, which seems belonging to you.

            Issue: {$type}
            URI: {$domarr[$i]}";
        $result = mail($mailrcpt, $subject, $body, $header);
        if (!$result) {
            echo error_get_last()['message'] . "<br />";
        } else {
            echo "mail has been successfully sent to {$mailrcpt}.<br />";
        }
    }
    include "form.php";
