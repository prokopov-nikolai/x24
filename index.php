<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include 'bootstrap.php';

$cache = new CacheFile();

$req = json_decode(file_get_contents('php://input'));

$telegram = new Telegram($config['telegram_token']);
$tgMsg = [
    'chat_id' => $req->message->chat->id,
    'parse_mode' => 'HTML',
    'text' => 'Ничего не найдено'
];

if (isset($req->message)) {
    $search = trim($req->message->text);
    $googlesheets = new Googlesheets($config['googlesheet_database_url'], $cache);
    $result = $googlesheets->search($search);

    if (!empty($result)) {
        $tgMsg['text'] = '';
        foreach ($result as $row) {
            $tgMsg['text'] .= $row[0] . ', размер ' . $row[1] . ', количество: ' . $row[2] . "\r\n";
        }
    }
}

$telegram->SendMessage($tgMsg);