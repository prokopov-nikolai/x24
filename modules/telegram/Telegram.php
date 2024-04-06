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
     *  $data['text'] = "";
     *  $data['parse_mode'] = 'HTML';
     *  $data['chat_id'] = int
     * @param $data
     */
    public function SendMessage(array $data): string
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.telegram.org/bot' . $this->telegramToken . '/sendMessage',
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_POSTFIELDS => $data,
            )
        );
        $sResponse = curl_exec($curl);
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
