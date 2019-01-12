<?php
    include('vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

$telegram = new Api('749857527:AAGMZgPom3lE7t_wHcxDC9YmTgRju_6Ll40'); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя


if($text){
    if ($text == "/start") {
        $reply = "Добро пожаловать в бота!";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
    }elseif ($text == "/open") {
        $url = 'http://31.202.46.87:8080/protect/status.xml';

        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'login' => 'admin',
                'password' => 'vkmodule'
            )
        )));
        $url = 'http://31.202.46.87:8080/protect/leds.cgi';
        $params = array(
            'led' => '0', // в http://localhost/post.php это будет $_POST['param1'] == '123'
            'timeout' => '0', // в http://localhost/post.php это будет $_POST['param2'] == 'abc'
        );

        $result .= file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));
        $reply = $result;
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif ($text == "/link") {
        $reply = "тут будет линка";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }else{
        $reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
    }
}else{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
}
    ?>