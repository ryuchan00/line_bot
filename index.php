<?php
/*
 * 環境変数として以下を使用しています
 * - LINE_CHANNEL_ID
 * - LINE_CHANNEL_SECRET
 * - LINE_CHANNEL_MID
 * - FIXIE_URL
 * - APP_NAME
 */

error_log("START: PHP");

$phpInput = json_decode(file_get_contents('php://input'));
$to = $phpInput->{"result"}[0]->{"content"}->{"from"};
$text = $phpInput->{"result"}[0]->{"content"}->{"text"};

$response_content = getResponseContent($text);
$post_data = ["to" => [$to], "toChannel" => "1383378250", "eventType" => "138311608800106203", "content" => $response_content];

$ch = curl_init("https://trialbot-api.line.me/v1/events");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, createHttpHeader());
curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
curl_setopt($ch, CURLOPT_PROXY, getenv("FIXIE_URL"));
curl_setopt($ch, CURLOPT_PROXYPORT, 80);
$result = curl_exec($ch);
curl_close($ch);

error_log(json_encode($result));
error_log("END: PHP");

function createHttpHeader() {
    $header = array(
        'Content-Type: application/json; charset=UTF-8',
        'X-Line-ChannelID: ' . getenv("LINE_CHANNEL_ID"),
        'X-Line-ChannelSecret: ' . getenv("LINE_CHANNEL_SECRET"),
        'X-Line-Trusted-User-With-ACL: ' . getenv("LINE_CHANNEL_MID")
    );
    return $header;
}

function getResponseContent($text) {
    if ($text == "のばら") {
        $imageUrl = "http://" . getenv("APP_NAME") . ".herokuapp.com/image/nobara.jpeg";
        return createImageResponse($imageUrl, $imageUrl);
    } else {
        return createTextResponse("合言葉を言ってください");
    }
}

function createTextResponse($message) {
    return ['contentType' => 1, "toType" => 1, "text" => $message];
}

function createImageResponse($imageUrl, $thumbnailImageUrl) {
    return ['contentType' => 2, "toType" => 1, 'originalContentUrl' => $imageUrl, "previewImageUrl" => $thumbnailImageUrl];
}
