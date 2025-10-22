<?php

namespace App\Http\Controllers\SMS;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BulkSmsController extends Controller
{

    protected $telefone;
    protected $mensagem;

    public function __construct($telefone, $mensagem)
    {
        $this->telefone = $telefone;
        $this->mensagem = $mensagem;
    }

    public function sendSmsAgendamento( )
    {
        try{
                // Your Account SID and Auth Token from twilio.com/console
            $sid    = env( 'TWILIO_SID' );
            $token  = env( 'TWILIO_TOKEN' );
            $client = new Client( $sid, $token );

                $numbers_in_arrays = explode( ',' , $this->telefone);

                $count = 0;

                foreach( $numbers_in_arrays as $number )
                {
                    $number = trim($number);  // remover espaços extras

                    if (empty($number)) {
                        continue; // pula números vazios
                    }

                    // Aqui poderia ter uma validação simples do formato do número, se quiser

                    $client->messages->create(
                        $number,
                        [
                            'from' => env('TWILIO_FROM'),
                            'body' => $this->mensagem,
                        ]
                    );
                    $count++;
                }

                return back()->with( 'success', $count . " messages sent!" );
        } catch (\Throwable $th) {
            Log::error('Erro ao registrar usuario: '. $th->getMessage());
            return response()->json(['error' => 'Erro ao registrar usuario'], 500);
        }    
   }
}
