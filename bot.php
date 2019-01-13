<?php
    include('vendor/autoload.php'); //Подключаем библиотеку
    use Telegram\Bot\Api;
    use RestClient\Client;

$telegram = new Api('749857527:AAGMZgPom3lE7t_wHcxDC9YmTgRju_6Ll40'); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Открыть"]]; //Клавиатура
$access = array("Nikita_Bessonov", "Ptichka1992");

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
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
    }elseif (($text == "/open" or $text == "Открыть") && (in_array($name, $access))) {
        openDoor();
        $reply = "Ok ";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif ($text == "/link") {
        $reply = "тут будет линка";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }else{
        $reply = "В доступе отказано";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
    }
}else{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
}
    ?>