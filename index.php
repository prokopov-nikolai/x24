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
    'text' => ''
];

if (isset($req->message)) {
    $search = trim($req->message->text);
    $googlesheets = new Googlesheets($config['googlesheet_database_url'], $cache);
    $result = $googlesheets->searchOne($search);

    if (!empty($result)) {
        $tgMsg['text'] = $result;
    }
}

$telegram->SendMessage($tgMsg);