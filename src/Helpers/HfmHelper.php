<?php

namespace AnatolieGhebea\LaravelHfm\Helpers;

// HELPER CLASS
// Add helper functions that can be used in every poiny of the application
class HfmHelper
{
    /**
     * This method will unset all the kesy that don't match the list of keys passe int the variable $keysToMatch
     * @param Array $arr the origial array
     * @param Array $keysToMatch the list of the keys to keep
     * @return Array the resulting array
     */
    public static function arrayUnsetUnmachingKeys($arr, $keysToMatch = [])
    {
        if (empty($arr) || empty($keysToMatch)) {
            return $arr;
        }

        $keysToUnset = [];
        foreach ($arr as $k => $v) {
            if (! in_array($k, $keysToMatch)) {
                $keysToUnset[] = $k;
            }
        }

        if (! empty($keysToUnset)) {
            foreach ($keysToUnset as $kk => $id) {
                if (isset($arr[$id])) {
                    unset($arr[$id]);
                }
            }
        }

        return $arr;
    }

    /**
     * This method will unset all the kesy that have been passed via the $keysToUnset variable.
     * @param Array $arr the origial array
     * @param Array $keysToUnset the list of the keys to keep
     * @return Array the resulting array
     */
    public static function arrayUnsetKeyList($arr, $keysToUnset = [])
    {
        if (empty($arr) || empty($keysToUnset)) {
            return $arr;
        }

        if (! empty($keysToUnset)) {
            foreach ($keysToUnset as $kk => $id) {
                if (isset($arr[$id])) {
                    unset($arr[$id]);
                }
            }
        }

        return $arr;
    }

    /**
     *
     */
    public static function checkValidDate($dateString, $fomrmat = 'Y-m-d')
    {
        $y = $m = $d = null;

        switch ($fomrmat) {
            case 'd/m/Y':
                $bits = explode("/", $dateString);
                if (count($bits) != 3) {
                    return false;
                } else {
                    if (strlen($bits[0]) != 2 || ! is_numeric($bits[0])) {
                        return false;
                    }
                    if (strlen($bits[1]) != 2 || ! is_numeric($bits[1])) {
                        return false;
                    }
                    if (strlen($bits[2]) != 4 || ! is_numeric($bits[2])) {
                        return false;
                    }

                    $d = intval($bits[0]);
                    $m = intval($bits[1]);
                    $y = intval($bits[2]);
                }

                break;

            default: // Y-m-d
                $bits = explode("-", $dateString);
                
                if (count($bits) != 3) {
                    return false;
                } else {
                    if (strlen($bits[0]) != 4 || ! is_numeric($bits[0])) {
                        return false;
                    }
                    if (strlen($bits[1]) != 2 || ! is_numeric($bits[1])) {
                        return false;
                    }
                    if (strlen($bits[2]) != 2 || ! is_numeric($bits[2])) {
                        return false;
                    }

                    $y = intval($bits[0]);
                    $m = intval($bits[1]);
                    $d = intval($bits[2]);
                }
                
                break;
        }

        $valid = checkdate($m, $d, $y);

        return $valid;
    }

    /**
     *
     */
    public static function isMenuItemActive($name)
    {
        // @todo
    }

    /**
     * Normalize null string
     * non posso scrivere null nel DB, quindi normalizzo a stringa vuota
     */
    public static function nullToString($str)
    {
        return $str == null ? '' : $str;
    }
}
