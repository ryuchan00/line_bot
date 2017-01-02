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
$message = $profile["displayName"] . "さん、ランダムでスタンプで返答します。";
$displayName = $profile["displayName"];

$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

$sql = 'insert into user (name) values (?)';
$stmt = $pdo->prepare($sql);
$flag = $stmt->execute(array($displayName));

if ($flag){
    error_log('データの追加に成功しました');
}else{
    error_log('データの追加に失敗しました');
}

// 返答するLINEスタンプをランダムで算出
$stkid = mt_rand(1, 17);

$bot->replyMessage($event->getReplyToken(),
  (new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder())
    ->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message))
    ->add(new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(1, $stkid))
);

// $bot->replyMessage($event->getReplyToken(),
//   (new \LINE\LINEBot\MessageBuilder\TemplateActionBuilder\())
//     ->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message))
//     ->add(new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(2, 19))
//     ->add(new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(2, 20))
//);
}

?>
