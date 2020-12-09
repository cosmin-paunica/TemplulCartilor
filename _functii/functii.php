<?php

function genereaza_token($n = 10) {
    $pool = "";
    for ($i = ord("A"); $i <= ord("Z"); $i++)
        $pool .= chr($i);
    for ($i = ord("a"); $i <= ord("z"); $i++)
        $pool .= chr($i);
    for ($i = 0; $i <= 9; $i++)
        $pool .= $i;

    $token = '';
    srand(time());
    for ($i = 0; $i < $n; $i++) {
        $token .= $pool[rand(0, strlen($pool) - 1)];
    }

    return $token;
}

function rezultat_recaptcha($response_key, $user_ip) {
    $secret_key = "6LcE1v8ZAAAAAJvABYeH3YjWXjxDRLSVlinBX89D";
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$response_key&remoteip=$user_ip";
    $response = file_get_contents($url);
    $response = json_decode($response);
    return $response->success;
}

?>