<?php

namespace App\Rules;

use Closure;
use DateTime;
use Illuminate\Contracts\Validation\ValidationRule;

class DataHoraValida implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //dd($value);

        $dataREpost = new DateTime($value);
        $dateToday = new DateTime('now');

        if($dataREpost < $dateToday ){
            $fail("A data é inferior a data a atual!");
        }  
    }
}