<?php
/**
 * Created by IntelliJ IDEA.
 * User: CBASE86
 * Date: 2017/02/03
 * Time: 17:55
 */

// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'GET') {
    foreach ($_GET as $k => $v) {
        if ($k == "PIC") {
            echo '<img src="' . $v . '""></br>';
        }
        echo "受信パラメータ" . $k . "：" . $v . "</br>";
    }
}

/**
 * リクエストメソッドを取得
 * @return str GET/POST/PUTなど
 */
function get_request_method()
{
    return $_SERVER['REQUEST_METHOD'];
}
