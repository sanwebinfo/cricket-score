<?php

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-type:application/json; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow', true);

function fetchWebpageContent($url) {

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt( $ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt( $ch, CURLOPT_TIMEOUT, 20);
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36');
    $response = curl_exec($ch);

    if($response === false) {
        throw new Exception("cURL error: " . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($httpCode >= 400) {
        throw new Exception("HTTP error $httpCode occurred while fetching the webpage.");
    }

    curl_close($ch);

    return $response;
}


function validateURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL);
}


if(isset($_GET['url']) && validateURL($_GET['url'])) {
    $url = htmlspecialchars($_GET['url']);

    try {

        $html = fetchWebpageContent($url);

        $pattern = '/\/(\d{5})\//';

        if (preg_match($pattern, $html, $matches)) {
            $number = $matches[1];
            $response = array("success" => true, "message" => "$number");
        } else {
            $response = array("success" => false, "message" => "No 5-digit number found on the webpage.");
        }
    } catch (Exception $e) {
        $response = array("success" => false, "message" => "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_HTML5));
    }
} else {
    $response = array("success" => false, "message" => "Invalid URL provided.");
}

echo json_encode($response);

?>