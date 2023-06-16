<?php
declare(strict_types=1);

/**
 * country 国家
 * province 省份
 * city 地市
 * isp 运营商
 */


$ip = $_GET["ip"] ?? "127.0.0.1";

$options = [
    CURLOPT_URL => "https://www.ip138.com/iplookup.asp?ip=" . $ip . "&action=2",
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

$data = mb_convert_encoding($data, "UTF-8", "GB18030");
if (preg_match("/ip_result = ({.*?});/", $data, $matches)) {
    $json = json_decode($matches[1], true);
    $ip_c = $json["ip_c_list"][0];
    echo json_encode(["country" => $ip_c["ct"], "province" => $ip_c["prov"], "city" => $ip_c["city"], "isp" => $ip_c["yunyin"]], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["message" => "查询失败"], JSON_UNESCAPED_UNICODE);
}

