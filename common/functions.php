<?php
    function get_resolver($host) {
        $domainparts = explode('.', $host);
        $temp = "";
        $resolver = [];
        for ($i = count($domainparts); $i > 0; $i--){
            $domain = $domainparts[$i-1] . "." . $temp;
            $temp = $domain;
            $res = dns_get_record($domain, DNS_NS)[0]["target"];
            if ($res){
                array_unshift($resolver, $res);
            }
        }
        return $resolver[0];
    }
