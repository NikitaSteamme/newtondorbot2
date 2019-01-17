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
$admin = array("Nikita_Bessonov");

function openDoor(){
    $client = new Client('http://admin:vkmodule@31.202.46.87:8080/protect');
    $request = $client->newRequest('/leds.cgi?led=0&timeout=0');
    $request->getResponse();
    sleep(1);
    $request = $client->newRequest('/leds.cgi?led=0&timeout=0');
    $request->getResponse();
}

final class FileReader
{
    protected $handler = null;
    protected $fbuffer = "";


    /**
     * Конструктор класса, открывающий файл для работы
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        if(!($this->handler = fopen($filename, "rb")))
            throw new Exception("Cannot open the file");
    }


    /**
     * Построчное чтение всего файла с учетом сдвига
     *
     * @return string
     */
    public function ReadAll()
    {
        if(!$this->handler)
            throw new Exception("Invalid file pointer");

        while(!feof($this->handler))
            $this->fbuffer .= fgets($this->handler);

        return $this->fbuffer;
    }


    /**
     * Установить строку, с которой производить чтение файла
     *
     * @param int  $line
     */
    public function SetOffset($line)
    {
        if(!$this->handler)
            throw new Exception("Invalid file pointer");

        while(!feof($this->handler) && $line--) {
            fgets($this->handler);
        }
    }
};

if($text){
    if ($text == "/start") {
        $reply = "Добро пожаловать в бота!";
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    }elseif (($text == "/open" or $text == "Открыть") && (in_array($name, $access))) {
        openDoor();
        $reply = "Ok ";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif ($text == "/link") {
        $reply = "тут будет линка";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif (mb_strtolower (explode(" " , $text)[0]) == "check_access") {

        $reply = "ошибка\n";

        $stream = new FileReader("access.txt");
        $stream->SetOffset(0);
        $reply = $stream->ReadAll();
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);

    }elseif (mb_strtolower (explode(" " , $text)[0]) == "give_access") {
        $files = array();
        $file = 'access.txt';
        // Новый человек, которого нужно добавить в файл
        $person = explode(" ", $text)[1];
        // Пишем содержимое в файл,
        // используя флаг FILE_APPEND для дописывания содержимого в конец файла
        // и флаг LOCK_EX для предотвращения записи данного файла кем-нибудь другим в данное время
        $fp = fopen("access.txt", "a"); // Открываем файл в режиме записи
        $mytext = $person."\r\n"; // Исходная строка
        $test = fwrite($fp, $mytext); // Запись в файл
        if ($test) $reply = 'Данные в файл успешно занесены.';
        else $reply = 'Ошибка при записи в файл.';
        fclose($fp); //Закрытие файла

        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }else{
        $reply = "В доступе отказано";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
    }
}else{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
}
