<?php

namespace App\Rules;

use Closure;
use DateTime;
use Illuminate\Contracts\Validation\ValidationRule;

class TimeValidate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $hora = new DateTime();
        $horaSistema = new DateTime($value);

        //dd($horaSistema, $hora);

        if($horaSistema->format('H:i:s') < $hora->format('H:i:s')){
            $fail("não é possivel agendar uma consutla em horario retroativo");
        }
    }
}
