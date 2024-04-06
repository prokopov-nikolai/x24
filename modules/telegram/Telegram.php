<?php
declare(strict_types=1);

class Telegram
{

    private string $telegramToken = '';

    public function __construct($telegramToken)
    {
        $this->telegramToken = $telegramToken;
    }

    /**
     *  $aData['text'] = "";
     *  $aData['parse_mode'] = 'HTML';
     *  $aData['chat_id'] = int
     * @param $aData
     */
    public function SendMessage(array $aData): string
    {
        $oCurl = curl_init();
        curl_setopt_array(
            $oCurl,
            array(
                CURLOPT_URL => 'https://api.telegram.org/bot' . $this->telegramToken . '/sendMessage',
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_POSTFIELDS => $aData,
            )
        );
        $sResponse = curl_exec($oCurl);
//            file_put_contents(Config::Get('path.root.server') . '/uploads/telegram.answer.txt', $sResponse);
        return $sResponse;
    }

    public function getTelegramToken(): string
    {
        return $this->telegramToken;
    }

    public function setTelegramToken(string $telegramToken): void
    {
        $this->telegramToken = $telegramToken;
    }
}
