<?php

include '../class/store.php';
(new DevCoder\DotEnv('../.env'))->load();

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-type:application/json; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow', true);

$url = getenv('URL');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt( $ch, CURLOPT_ENCODING, 'gzip');
curl_setopt( $ch, CURLOPT_TIMEOUT, 20);
curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36');
$response = curl_exec($ch);

if($response === false) {
    $error = array("error" => "cURL Error: " . curl_error($ch));
    echo json_encode($error);
    exit;
}

curl_close($ch);

$data = array();

if (preg_match_all('/<a class="text-hvr-underline text-bold" href="\/live-cricket-scores\/(\d{5})\/([^"]+)" title="([^"]+)">/', $response, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
        $id = htmlspecialchars($match[1]);
        $title = htmlspecialchars($match[3]);
        $data[] = array("title" => $title, "id" => $id);
    }

    echo json_encode($data);

} else {

    $error = array("error" => "No matches found");
    echo json_encode($error);
}

?>