<?php
$accessToken = 'NcNXkgzBiEABNxdNOk9mGVv2IBL/04HevKtJfozIRYhxFE9OMFDrc8GYoLiDe++VIzUSIvpjNyE9RxKqqgOFjD4kTaR7EjrXK7g3B5vr7JFeFOmAusk7IdvNiLfPthizidIfbfEl0MT9fHxpIZHzfgdB04t89/1O/w1cDnyilFU=';


//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

$type = $jsonObj->{"events"}[0]->{"message"}->{"type"};
//メッセージ取得
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

//メッセージ以外のときは何も返さず終了
if($type != "text"){
	exit;
}

$response_format_text = getResponseContent($text);

//返信データ作成
// $response_format_text = [
// 	"type" => "text",
// 	"text" => "金剛デース！"
// 	];
$post_data = [
	"replyToken" => $replyToken,
	"messages" => [$response_format_text]
];

$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
    ));
$result = curl_exec($ch);
curl_close($ch);

function getResponseContent($text) {
    if ($text == "のばら") {
        // $imageUrl = "http://" . getenv("APP_NAME") . ".herokuapp.com/image/nobara.jpeg";
        $imageUrl = "http://linebot1234.herokuapp.com/image/nobara.png";
        return createImageResponse($imageUrl, $imageUrl);
    } else {
        // return createTextResponse("合言葉を言ってください");
        $imageUrl = "http://linebot1234.herokuapp.com/image/nobara.png";
        return createImageResponse($imageUrl, $imageUrl);
    }
}

function createTextResponse($message) {
    return ["type" => "text", "text" => $message];
}

function createImageResponse($imageUrl, $thumbnailImageUrl) {
    return ["type" => "image", "originalContentUrl" => $imageUrl, "previewImageUrl" => $thumbnailImageUrl];
}

/*
 * 環境変数として以下を使用しています
 * - LINE_CHANNEL_ID
 * - LINE_CHANNEL_SECRET
 * - LINE_CHANNEL_MID
 * - FIXIE_URL
 * - APP_NAME
 */
/*
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
*/
