<?php
namespace App\Core;


class ImageFactory
{
    public static $pathImage;

    public function __construct($generateFolder = null) // auto create or not
    {
        if(!is_dir(self::$pathImage)):

            switch ($generateFolder) {
                case 'auto create':
                    if(!mkdir($target,0777,true))
                        echo "failed create folder";
                    break;
                
                default:
                    echo "Path directory is not found!";    
                        return false;
                    break;
            }

        endif;
    }

    public static function imageExist($img)
    {
        if( file_exists(self::$pathImage . $img) && is_file(self::$pathImage . $img) ):
            return true;
        else :
            return false;
        endif;
    }

    public function uploadImage($files)
    {
        $name=$files['name'];
        $tmp =$files['tmp_name'];
        $err =$files['error'];
        $ext = explode('.',$name);
        $ext = end($ext);
        // var_dump($_FILES);

        if(!$err)
        {
            $rand = self::generateRandName();

            $names = $rand.'.'.$ext;

            if(move_uploaded_file($tmp, self::$pathImage.$names ))
            {
                $result['name'] = $names;
                $result['res'] = true;
            } else {
                $result['res'] = false;
            }

            return $result;
        }
    }

    public function deleteImage($img)
    {
        if(self::imageExist($img))
        {
            if(unlink(self::$pathImage . $img)):
                return true;
            else:
                return false;
            endif;
        } else {
            echo "File tidak ditemukan!";
            return false;
        }
    }

    private static function generateRandName($lenght = 10)
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
}
