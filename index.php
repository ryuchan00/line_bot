<?php

require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
try {
  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
  error_log("parseEventRequest failed. InvalidSignatureException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
  error_log("parseEventRequest failed. UnknownEventTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
  error_log("parseEventRequest failed. UnknownMessageTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
  error_log("parseEventRequest failed. InvalidEventRequestException => ".var_export($e, true));
}

foreach ($events as $event) {
    if ($event instanceof \LINE\LINEBot\Event\PostbackEvent) {
        replyTextMessage($bot, $event->getReplyToken(), "Postback受信「" . $event->getPostbackData() . "」");
        continue;
    }
    
    if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
        error_log('Non message event has come');
        continue;
    }
    if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
        error_log('Non text message has come');
        continue;
    }


// $bot->replyText($event->getReplyToken(), $event->getText());
    $profile = $bot->getProfile($event->getUserId())->getJSONDecodedBody();
    $message = "http://codezine.jp/article/detail/9905";
//$message = $profile["displayName"] . "さん、ランダムでスタンプで返答します。";
    $displayName = $profile["displayName"];

    foreach ($profile as $k => $v) {
        error_log($k . ":" . $v);
    }

//$url = parse_url(getenv('DATABASE_URL'));
//$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
//$pdo = new PDO($dsn, $url['user'], $url['pass']);
//
//$sql = 'insert into user (name) values (?)';
//$stmt = $pdo->prepare($sql);
//$flag = $stmt->execute(array($displayName));

//if ($flag){
//    error_log('データの追加に成功しました');
//}else{
//    error_log('データの追加に失敗しました');
//}

// 返答するLINEスタンプをランダムで算出
    $stkid = mt_rand(1, 17);

//$bot->replyMessage($event->getReplyToken(),
//  (new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder())
//    ->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message))
//    ->add(new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(1, $stkid))
//);


//    replyImageMessage($bot, $event->getReplyToken(), "https://" . $_SERVER["HTTP_HOST"] . "/imgs/original.jpg", "https://" . $_SERVER["HTTP_HOST"] . "/imgs/preview.jpg");
    replyButtonsTemplate($bot,
        $event->getReplyToken(),
        "お天気お知らせ - 今日は天気予報は晴れです",
        "https://" . $_SERVER["HTTP_HOST"] . "/imgs/template.jpg",
        "お天気お知らせ",
        "今日は天気予報は晴れです",
        new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder (
            "明日の天気", "tomorrow"),
        new LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder (
            "週末の天気", "weekend"),
        new LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder (
            "Webで見る", "http://google.jp")
    );

}

function replyImageMessage($bot, $replyToken, $originalImageUrl, $previewImageUrl) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));
    if (!$response->isSucceeded()) {
        error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
}

function replyButtonsTemplate($bot, $replyToken, $alternativeText, $imageUrl, $title, $text, ...$actions) {
    $actionArray = array();
    foreach($actions as $value) {
        array_push($actionArray, $value);
    }
    $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
        $alternativeText,
        new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder ($title, $text, $imageUrl, $actionArray)
    );
    $response = $bot->replyMessage($replyToken, $builder);
    if (!$response->isSucceeded()) {
        error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
}

?>