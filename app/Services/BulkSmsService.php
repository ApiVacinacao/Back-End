<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class BulkSmsService
{
    protected $client;

    public function __construct()
    {
        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_TOKEN');

        $this->client = new Client($sid, $token);
    }

    /**
     * Envia SMS para uma lista de números.
     *
     * @param string $telefones String com números separados por vírgula
     * @param string $mensagem  Mensagem de texto
     * @return int Quantidade de mensagens enviadas
     */
    public function send(string $telefones, string $mensagem): int
    {
        $numbers = array_map('trim', explode(',', $telefones));
        $count = 0;

        foreach ($numbers as $number) {
            if (empty($number)) {
                continue;
            }

            try {
                $this->client->messages->create(
                    $number,
                    [
                        'from' => env('TWILIO_FROM'),
                        'body' => $mensagem,
                    ]
                );
                $count++;
            } catch (\Throwable $th) {
                Log::error("Erro ao enviar SMS para {$number}: " . $th->getMessage());
            }
        }

        return $count;
    }
}
