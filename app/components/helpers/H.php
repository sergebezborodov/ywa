<?php

/**
 * Helper function
 */
class H {

    /**
     * Set seed for random generator
     *
     * @static var bool $thisProcessHasBeenInitialized
     * @return void
     */
    public static function randomizeProcessSeed() {
        static $thisProcessHasBeenInitialized;

        if ($thisProcessHasBeenInitialized) {
                return;
        }

        list($usec, $sec) = explode(' ', microtime());
        mt_srand((10000000000 * (float)$usec) ^ (float)$sec);

        $thisProcessHasBeenInitialized = true;
    }

    /**
     * Generate random string
     *
     * @param int $len
     * @param string $type
     * @return string
     */
    public static function generateRandomString($len = 32, $type = 'default') {
        self::randomizeProcessSeed();
        $randomText = null;
        for($i = 0; $i < $len; $i++) {
            $temp = mt_rand(1,3);
            if($i == 0 and $type == "var") {
                $temp2 = mt_rand(1,2);
                if ($temp2 == 1) {
                    $randomText .= chr(mt_rand(65,90));
                } else {
                    $randomText .= chr(mt_rand(97,122));
                }
            } else {
                if ($temp == 1) {
                    $randomText .= chr(mt_rand(65,90));
                } elseif ($temp == 2) {
                    $randomText .= chr(mt_rand(97,122));
                } else {
                    $randomText .= chr(mt_rand(48,57));
                }
            }
        }
        return $randomText;
    }

    /**
     * Return human format for file size
     *
     * @static
     * @param $size
     * @param string $sep
     * @return string
     */
    public static function fileSize($size, $sep = ' ') {
        $unit = null;
        $units = array('byte', 'kb', 'Mb', 'Gb', 'Tb');

        for($i = 0, $c = count($units); $i < $c; $i++) {
            if ($size > 1024)
            {
                $size = $size / 1024;
            }
            else
            {
                $unit = $units[$i];
                break;
            }
        }

        return round($size, 2).$sep.$unit;
    }


    /**
     * Generate sql date form
     *
     * @static
     * @param null $timestamp
     * @return string
     */
    public static function sqldate($timestamp = null) {
        $format = "Y-m-d H:i:s";
        return (empty($timestamp)) ? date($format) : date($format, $timestamp);
    }
}
