<?php

namespace App\Http\Controllers\test;

use Illuminate\Http\Request;
use Mail;

class testeEmail
{
    public function senEmail(){
        Mail::to('lagado75@gmail.com')->send(new \App\Mail\AgendamentoEmail());

        return response()->json("email enviado");
    }
}
