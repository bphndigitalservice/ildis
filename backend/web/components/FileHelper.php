<?php

// FileHelper.php
namespace backend\web\components;

use yii\base\Module;

class FileHelper extends Module
{
    public static function sanitizeFilename($filename, $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'])
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) 
       {
            // Replace the extension with .txt
            $pattern = '/' . $extension  . '/i';
            $filename =  strtolower(preg_replace($pattern, 'txt', $filename));
            return $filename; // Return the modified filename
        }

        return $filename;
    }
}
