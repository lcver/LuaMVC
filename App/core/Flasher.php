<?php

/**
 * 
 * send report after action
 */

class Flasher
{
    public static $message;

    public static function setFlash($message, $result)
    {
        $result = $result ? "success" : "danger";

        self::$message = "
        <div class='callout callout-".$result."'>
            <h5>".$message."!</h5>
        </div>";

        $_SESSION['Flash'] = self::$message;
    }

    public static function get()
    {
        if(isset($_SESSION['Flash'])){
            $res = $_SESSION['Flash'];
            unset($_SESSION['Flash']);

            return $res;
        }
    }

}