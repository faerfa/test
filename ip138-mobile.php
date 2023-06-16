<?php
declare(strict_types=1);

/**
 * carrier 运营商
 * province 省份
 * city 地市
 */


$phoneNumber = $_GET["phone_number"] ?? "0000";

$options = [
    CURLOPT_URL => "https://www.ip138.com/mobile.asp?mobile=" . $phoneNumber . "&action=mobile",
    CURLOPT_HTTPHEADER => [
        "Host: www.ip138.com",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36"
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
];
$handle = curl_init();
curl_setopt_array($handle, $options);
$data = curl_exec($handle);
if (curl_errno($handle) !== 0) {
    http_response_code(500);
    echo json_encode(["message" => "请求失败"], JSON_UNESCAPED_UNICODE);
    exit();
}
curl_close($handle);

if (preg_match_all("/<td><span>(.*?)<\/span><\/td>|rel=\"nofollow\">中国(.*?)<\/a>/", $data, $matches)) {
    $s = explode(" ", $matches[1][0]);
    echo json_encode(["carrier" => "中国" . $matches[2][1], "province" => $s[0], "city" => $s[1]], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode(["message" => "查询失败"], JSON_UNESCAPED_UNICODE);
}


