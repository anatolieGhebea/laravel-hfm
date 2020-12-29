<?php

namespace AnatolieGhebea\LaravelHfm\Traits;

use AnatolieGhebea\LaravelHfm\Helpers\HfmHelper;
trait FieldsMapTrait
{
    /**
     * Given a monodimensional Array of options, a prop_name and a default_value, this method will check
     * if prop isset on the option array and will return the value, else will return the default value
     * @param Array $opts otions list
     * @param String $prop_name the name of the key/prop tho search in array
     * @param Mixed $default_value the value to be returned if @prop_name is non set
     *
     * @return Mixed $value
     */
    public static function getValueFromOpts($opts, $prop_name, $default_value = null)
    {
        if (isset($opts[$prop_name])) {
            return $opts[$prop_name];
        }

        return $default_value;
    }


    /**
     * This method will return only a subset of the FieldMap.
     * @param String $op    FM_KEEP will return only the fields specified in $list; 
     *                      FM_DROP will unset the fields in $list and return the remaining.
     * @param Array $list   The list of fields to kepp or to unset from the FieldMap 
     */
    public static function getFieldsMapSubset( $op = FM_KEEP , $list = [] ){
        $fields = self::getFieldsMap();

        if( !(count($list) > 0) )
            return $fields;

        switch($op){
            case FM_KEEP:
                $fields = HfmHelper::arrayUnsetUnmachingKeys( $fields, $list );
                break;
            case FM_DROP:
                $fields = HfmHelper::arrayUnsetKeyList( $fields, $list );
                break;
            
            default:
                break;
        }

        return $fields;
    }


    /**
     * Reurns the default validation rules for the model by calling getFieldsMap()
     * and checking which constraints must be applied
     * 
     * @return Array $rules
     */
    public static function getDefaultValidationRules()
    {
        $fields = self::getFieldsMap();
        $rules = self::composeRules($fields);
        return $rules;
    }

    /**
     * Retunr the validation rules for the input fields
     * @param Array $fields     Must be compatible wiht the FieldMap definition
     * @return Array $rules
     */
    public static function getValidationRulesForFields( $fields )
    {
        $rules = self::composeRules($fields);
        return $rules;
    }

    /**
     * @param Array 
     * @return Array 
     */
    private static function composeRules( $fields )
    {
        if( !is_array($fields) ){
            // throwException
            return false;
        }

        $rules = [];
        foreach ($fields as $fname => $fprops) {
            $rls = [];

            // check if required
            if (isset($fprops[FLD_REQUIRED]) && $fprops[FLD_REQUIRED]) {
                $rls[] = 'required';
            } else {
                $rls[] = 'nullable';
            }

            // check daataType
            if (isset($fprops[FLD_DATA_TYPE])) {
                switch ($fprops[FLD_DATA_TYPE]) {
                    case DT_INT:
                    case DT_FLOAT:
                    case DT_BOOLEAN:
                        $rls[] = 'numeric';
                        // no break
                    case DT_TEXT:
                    case DT_DATE:
                        $rls[] = 'max:255';
                }
            }

            $rules[$fname] = $rls;
        }

        return $rules;
    }
    
}
