<?php
error_log("callback start.");
// $accessToken = 'NcNXkgzBiEABNxdNOk9mGVv2IBL/04HevKtJfozIRYhxFE9OMFDrc8GYoLiDe++VIzUSIvpjNyE9RxKqqgOFjD4kTaR7EjrXK7g3B5vr7JFeFOmAusk7IdvNiLfPthizidIfbfEl0MT9fHxpIZHzfgdB04t89/1O/w1cDnyilFU=';
$accessToken = getenv("ACCESS_TOKEN");

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

error_log($json_string);

$type = $jsonObj->{"events"}[0]->{"message"}->{"type"};
//メッセージ取得¡
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

//メッセージ以外のときは何も返さず終了
if($type != "text"){
	exit;
}

$response_format_text = getResponseContent($text);
// $response_format_text = createTextResponse($text);
// $response_format_text += createTextResponse($text);

$post_data = [
	"replyToken" => $replyToken,
	"messages" => [$response_format_text]
];

error_log(json_encode($post_data, JSON_PRETTY_PRINT));

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

error_log("callback end.");

function getResponseContent($text) {
    // if ($text == "のばら") {
    //     // $imageUrl = "http://" . getenv("APP_NAME") . ".herokuapp.com/image/nobara.jpeg";
    //     return createTextResponse("きさまはんらんぐんだな");
    //     // $imageUrl = "http://linebot1234.herokuapp.com/image/test.jpg";
    //     // return createImageResponse($imageUrl, $imageUrl);
    // } else {
    //     return createTextResponse("合言葉を言ってください");
        // $imagePath = "http://linebot1234.herokuapp.com/image/test.jpg";
        $imagePath = "/app/image/test.jpg";
        return createImageResponse($imageUrl, $imageUrl);
    // }
    $q =<<< EOF
    <<< 心理テスト >>>
宝石は、黄、赤、緑、黒、茶、白、青、ピンクの8色があり、好きな色を選べます。
あなたなら、どの色の宝石でペンダントを完成させますか?
EOF;
    $a = array();
    $a["yellow"] =<<< EOF
表面的には明るく、人間関係もうまくいっているように見えて、じつは孤立感を抱えているのでは?
「話のわかるいい人」を演じすぎて、自分の本当の気持ちを言い出せなくなっているあなた。
落ち込みの原因は「孤独感」にあるようです。
EOF;
    $a["red"] =<<< EOF
誰かに腹を立てていませんか?
怒鳴りつけたいのに、正面切って言えない。
そんな鬱積した不満が心の底につもって、あなたを蝕んでいるようです。
落ち込みの原因は、ズバリ「怒り」でしょう。
EOF;
    $a["green"] =<<< EOF
「もっと頑張らなくては」と思っているのに、だるかったり、眠気を催して何もできないでいるのでは?
気力だけが空回りするのは、身体が休息を欲しているからに他なりません。
落ち込みの正体は「疲れ」にあるでしょう。
EOF;
    $a["black"] =<<< EOF
最近、プレッシャーにさらされたか、不安に直面したようですね。
かなりナーバスになっていて、心を閉ざしがちな様子がうかがえます。
そう、あなたを落ち込ませているのは「恐怖心」。
また傷つくのが怖いのです。
強がっても、ロクなことはありません、
EOF;
    $a["brawn"] =<<< EOF
満たされない気持ちを抱えていますね。
もっと上を目指したいのに、なかなか前に進めない。
あるいは、欲しいものが手に入らないでいるでしょう。
そんなあなたは、「焦り」が落ち込みの正体のようです。
無い物ねだりを繰り返しても、心が疲れるだけです。
EOF;
    $a["white"] =<<< EOF
最近、自己嫌悪に陥ることがあったのでは?
あなたは世俗にまみれた自分を恥ずかしく感じ、ピュアな存在に生まれ変わりたいと強く望んでいそうです。
落ち込みの原因は、自分自身の「心の汚れ」にあるでしょう。
EOF;
    $a["blue"] =<<< EOF
ステキな異性に巡り会えない。
志望のポストに手が届かない。
あるいは周囲の期待に応えられなくて、 みじめな気がする……。
そんなあなたの落ち込みの原因は、言うまでもなく「高すぎる理想」。
志が現実的なものかどうか、今一度検証する必要がありそうです。
EOF;
    $a["pink"] =<<< EOF
最近、仕事やプライベートでうれしい出来事があったようですね。
しかし、同時にそのことが喪失不安をも招いているようです。
あなたの落ち込みの原因は、「喜び」とそれを失う「恐れ」にあるでしょう。
EOF;

    // switch ($text) {
    //     case "黄";
    //     case "黄色";
    //         return createTextResponse($a["yellow"]);
    //     case "赤";
    //     case "赤色";
    //         return createTextResponse($a["red"]);
    //     case "緑";
    //     case "緑色";
    //         return createTextResponse($a["green"]);
    //     case "黒";
    //     case "黒色";
    //         return createTextResponse($a["black"]);
    //     case "茶";
    //     case "茶色";
    //         return createTextResponse($a["brawn"]);
    //     case "白";
    //     case "白色";
    //         return createTextResponse($a["white"]);
    //     case "青";
    //     case "青色";
    //         return createTextResponse($a["blue"]);
    //     case "ピンク";
    //     case "ピンク色";
    //         return createTextResponse($a["pink"]);
    //     default;
    //         return createTextResponse($q);
    // }
    // return makeTemplatePostData($text);
    // return createTextResponse(makeTemplatePostData($text));
}

function createTextResponse($message) {
    return ["type" => "text", "text" => $message];
}

function createImageResponse($imageUrl, $thumbnailImageUrl) {
    return ["type" => "image", "originalContentUrl" => $imageUrl, "previewImageUrl" => $thumbnailImageUrl];
}

function makeImagePostData($imagePath) {
    $response_format_text = [
        "type" => "image",
        "originalContentUrl" => $imagePath,
        "previewImageUrl" => $imagePath
    ];

    return $response_format_text;
}

function makeTemplatePostData($length) {
    return [
        "type" => "template",
        "altText" => "どの言葉にしますか？",
        "template" => [
            "type" => "buttons",
            "title" => "Menu",
            "text" => "作る文字列の種類を選んでください",
            "action" => makeButtonTemplateData($length)
        ]
    ];
}

function makeButtonTemplateData($length) {
    return
    [
        [
            "type" => "postback",
            "label" => "半角英数",
            "data" => "lang=half&length=" . $length
        ],
        [
            "type" => "postback",
            "label" => "全角日本語",
            "data" => "lang=half&length=" . $length
        ],
        [
            "type" => "postback",
            "label" => "半角記号",
            "data" => "lang=half&length=" . $length
        ]
    ];
}

// {
//     "type": "location",
//     "title": "my location",
//     "address": "〒150-0002 東京都渋谷区渋谷２丁目２１−１",
//     "latitude": 35.65910807942215,
//     "longitude": 139.70372892916203
// }

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
