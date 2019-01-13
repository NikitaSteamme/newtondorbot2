<?php
    include('vendor/autoload.php'); //Подключаем библиотеку
    use Telegram\Bot\Api;
    use RestClient\Client;

$telegram = new Api('749857527:AAGMZgPom3lE7t_wHcxDC9YmTgRju_6Ll40'); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя

function openDoor(){
    $client = new Client('http://admin:vkmodule@31.202.46.87:8080/protect');
    $request = $client->newRequest('/leds.cgi?led=0&timeout=0');
    $request->getResponse();
    sleep(1);
    $request = $client->newRequest('/leds.cgi?led=0&timeout=0');
    $request->getResponse();
}


if($text){
    if ($text == "/start") {
        $reply = "Добро пожаловать в бота!";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
    }elseif ($text == "/open") {
        openDoor();
        $reply = "Ok";
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