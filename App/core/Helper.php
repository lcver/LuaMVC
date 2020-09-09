<?php

class Helper
{
    public static function null_checker($request)
    {
        if(!is_null($request)){
            // count array key
            $arr_key = array_keys($request);
            $count = count($arr_key);

            /**
             * filter array
             * when array just have one row
             */
            $num = NULL;
            for ($i=0; $i < $count ; $i++) {
                if(is_numeric($arr_key[$i])) $num = true;
            }

                $response = !$num ? [$request] : $request;

        }else{
            $response = [];
        }
        return $response;
    }

    public static function generateString($lenght = 10)
    {
        $num = "1234567890";
        $alphaLower = "abcdefghijklmnopqrstuvwxyz";
        $alphaUpper = "ABCDEFGHIJKLMNPOQRSTUVWXYZ";

        $mix = $num.$alphaLower.$alphaUpper;
        $mix_lenght = strlen($mix);

        $rand = "";
        for ($i=0; $i < $lenght ; $i++) {
            $char = $mix[mt_rand(0, $mix_lenght - 1)];
            $rand .= $char;
        }

        return $rand;
    }

    public static function validationForm($validation, $form)
    {
        $result = true;
        
        // valid form
        for ($i=0; $i < count($validation) ; $i++) { 
            if(!array_key_exists($validation[$i], $form))
                $result = false;
        }

        // blank field
        /**
         * example: __BLANK__
         */
        foreach ($form as $key => $value) {
            if(is_string($value))
            {
                if(preg_match("/blank/i", $value))
                    $result = false;
            } else if (is_array($value))
            {
                for ($j=0; $j < count($value) ; $j++) { 
                    if(preg_match("/blank/i", $value[$j]))
                        $result = false;
                }
            }
        }

        return $result;
    }
}
