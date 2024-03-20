<?php

include './class/store.php';
(new DevCoder\DotEnv('./.env'))->load();

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-type:application/json; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow', true);

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$match = filter_var($id, FILTER_VALIDATE_REGEXP, [
    'options' => ['regexp' => '/^[0-9]+$/']
]);

if ($match === false || $match === null) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid ID format"]);
    exit;
}

$api_url = getenv('APIURL');

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $api_url . $match,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
]);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data from the API: " . curl_error($curl)]);
    curl_close($curl);
    exit;
}

curl_close($curl);

if (!$response || !is_valid_json($response)) {
    http_response_code(500);
    echo json_encode(["error" => "Invalid response from the API"]);
    exit;
}

echo $response;

function is_valid_json(string $string): bool {
    return json_decode($string) !== null;
}

?>