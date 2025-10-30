<?php

namespace App\Rules;

use Closure;
use DateTime;
use Illuminate\Contracts\Validation\ValidationRule;

class DataValida implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dataREpost = new DateTime($value);
        $dateToday = new DateTime();

        if($dataREpost < $dateToday ){
            $fail("A data é inferir a data a atual");
        }  
    }
}
