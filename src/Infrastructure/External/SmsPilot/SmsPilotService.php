<?php

namespace src\Infrastructure\External\SmsPilot;

use src\Infrastructure\Notification\Sms\SmsServiceInterface;
use src\Infrastructure\Environment\EnvWithDefault;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

class SmsPilotService implements SmsServiceInterface
{
    private Client $httpClient;
    private string $apiKey;
    private string $apiUrl;

    public function __construct(string $apiKey = null)
    {
        $this->httpClient = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);

        $this->apiUrl = (new EnvWithDefault('SMS_PILOT_API_URL', 'https://smspilot.ru/api.php'))->string();
        $this->apiKey = $apiKey ?? (new EnvWithDefault('SMS_PILOT_API_KEY', 'EMULATOR_KEY'))->string();
    }

    public function send(string $phone, string $message): bool
    {
        try {
            $response = $this->httpClient->get($this->apiUrl, [
                'query' => [
                    'send' => $message,
                    'to' => $this->formatPhone($phone),
                    'apikey' => $this->apiKey,
                    'format' => 'json',
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['error'])) {
                Yii::error(sprintf('SmsPilot API error: %s', json_encode($result['error'])));
                return false;
            }

            if (isset($result['send']) && is_array($result['send'])) {
                foreach ($result['send'] as $sendResult) {
                    if (isset($sendResult['status']) && $sendResult['status'] === '0') {
                        Yii::info(sprintf('SMS sent successfully to %s', $phone));
                        return true;
                    }
                }
            }

            Yii::error(sprintf('SmsPilot API unexpected response: %s', json_encode($result)));
            return false;

        } catch (GuzzleException $e) {
            Yii::error(sprintf('SmsPilot API request failed: %s', $e->getMessage()));
            return false;
        }
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) === 11 && $phone[0] === '8') {
            $phone = '7' . substr($phone, 1);
        }
        
        return $phone;
    }
}
