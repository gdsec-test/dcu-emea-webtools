<?php
    require_once '/usr/local/include/.apicredentials.php';
    $proxy = "http://proxy.hosteurope.de:3128";
    $domain = trim($_POST['url']);
    $type = $_POST['reason'];
    if (!is_string($type)){
        echo "No reason selected for transfer!";
        exit(1);
    }
    if (!isset($domain)){
        echo "No domain has been specified!";
        exit(1);
    }

    // check for current dir, to get this working on OTE and PRD without manipulation
    if (preg_match('/(\/var\/www\/dcubackup)/', getcwd())){
        $APIKEY = $OTEAPIKEY;
        $APISECRET = $OTEAPISECRET;
        $url = "https://api.ote-godaddy.com/v1/abuse/tickets";
    } elseif (preg_match('/(\/var\/www\/dcu\/)/', getcwd())){
        $APIKEY = $PRDAPIKEY;
        $APISECRET = $PRDAPISECRET;
        $url = "https://api.godaddy.com/v1/abuse/tickets";
    } else {
        echo "No valid environment. Exiting!";
        exit(127);
    }

    $domarr = explode("\r\n", $domain);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_PROXY, $proxy);

    $headers = array(
       "Accept: application/json",
       "Authorization: sso-key $APIKEY:$APISECRET",
       "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    for ($i = 0; $i < count($domarr); $i++){
        $data = <<<DATA
        {
          "info": "original incident available at DCU-EMEA",
          "intentional": false,
          "proxy": "no proxy needed",
          "source": "$domarr[$i]",
          "target": "no target specified",
          "type": "$type"
        }
DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        $rc = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        switch ($rc){
            case 200:
                echo "No response specified.<br />";
                break;
            case 201:
                echo "Success!<br />";
                break;
            case 401:
                echo "Authentication info not sent or invalid!<br />";
                break;
            case 403:
                echo "Authenticated user is not allowed access.<br />";
                break;
            case 422:
                echo "An error has been occured :(<br />";
                break;
        }
        print($resp);
        echo "<br />";
    }
    curl_close($curl);
    include "form.php";
?>
