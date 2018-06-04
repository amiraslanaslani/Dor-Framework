<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/28/18
 * Time: 11:45 PM
 */

namespace Dor\Util\Security;


class InputCheck
{
    const   TYPE_A_Z = -111.1,
            TYPE_a_z = -112.1,
            TYPE_0_9 = -113.1,
            TYPE____ = -114.1;

    private static function getTypeArray($type){
        switch ($type){
            case InputCheck::TYPE_0_9: return range('0', '9');
            case InputCheck::TYPE_a_z: return range('a', 'z');
            case InputCheck::TYPE_A_Z: return range('A', 'Z');
            case InputCheck::TYPE____: return array('_');
            default: return array();
        }
    }

    public static function cleanRawText(string $string): string{

    }

    public static function isJustContains($string, ...$types){
        $acceptArray = array();
        foreach($types as $type){
            $acceptArray = array_merge($acceptArray, InputCheck::getTypeArray($type));
        }

        foreach ($string as $char){
            if(array_search($char, $acceptArray) === false)
                return false;
        }

        return true;
    }
}