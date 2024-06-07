<?php

include '../class/store.php';
(new DevCoder\DotEnv('../.env'))->load();

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-type:application/json; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow', true);

$url = getenv('LIVE');

$ch = curl_init();

if ($ch === false) {
    http_response_code(500);
    echo json_encode(array("error" => "Failed to initialize cURL"));
    exit;
}

try {
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36');
    $response = curl_exec($ch);

    if ($response === false) {
        throw new Exception('cURL Error: ' . curl_error($ch));
    }
    
    curl_close($ch);

    if (!is_string($response) || empty($response)) {
        throw new Exception('Invalid response received from the server.');
    }

    $data = array();

    if (preg_match_all('/<a class="text-hvr-underline text-bold" href="\/live-cricket-scores\/(\d{5})\/([^"]+)" title="([^"]+)">/', $response, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $id = htmlspecialchars($match[1], ENT_QUOTES, 'UTF-8');
            $title = htmlspecialchars($match[3], ENT_QUOTES, 'UTF-8');
            $data[] = array("title" => $title, "id" => $id);
        }

        http_response_code(200);
        echo json_encode($data);

    } else {
        $id = '0';
        $title = 'Currently No Live Match';
        $data[] = array("title" => $title, "id" => $id);
        http_response_code(200);
        echo json_encode($data);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("error" => $e->getMessage()));
}

?>