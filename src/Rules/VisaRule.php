<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class VisaRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        //Remove whitespaces and -
        $value = preg_replace('/\D/', '', $value);
        
        //Check the card matches the Visa pattern
        if (substr($value, 0, 1) == "4") {
            
            /* Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org *
            * This code has been released into the public domain, however please      *
            * give credit to the original author where possible.                      */
            
            // Set the string length and parity
            $number = $value;
            $number_length=strlen($number);
            $parity=$number_length % 2;
        
            // Loop through each digit and do the maths
            $total=0;
            for ($i=0; $i<$number_length; $i++) {
                $digit=$number[$i];
                // Multiply alternate digits by two
                if ($i % 2 == $parity) {
                    $digit*=2;
                    // If the sum is two digits, add them together (in effect)
                    if ($digit > 9) {
                        $digit-=9;
                    }
                }
                // Total up the digits
                $total+=$digit;
            }

            // If the total mod 10 equals 0, the number is valid
            return ($total % 10 == 0) ? TRUE : FALSE;
            
        }
        
        return false;
    }

    public function error()
    {
        return '{field} is not a valid Visa card number.';
    }
}
