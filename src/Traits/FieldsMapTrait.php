<?php 

namespace AnatolieGhebea\LaravelHfm\Traits;

trait FieldsMapTrait {

    /**
     * Given a monodimensional Array of options, a prop_name and a default_value, this method will check 
     * if prop isset on the option array and will return the value, else will return the default value
     * @param Array $opts otions list
     * @param String $prop_name the name of the key/prop tho search in array 
     * @param Mixed $default_value the value to be returned if @prop_name is non set
     * 
     * @return Mixed $value
     */
    public static function getValueFromOpts( $opts, $prop_name, $default_value = null ){
        if( isset( $opts[$prop_name] ) )
            return $opts[$prop_name];

        return $default_value;

    } 

    public static function getDefaultValidationRules(){
        $fields = self::getFieldsMap();
        $rules = [];
        foreach($fields as $fname => $fprops ) {
            $rls = [];
            if( isset( $fprops[FLD_REQUIRED] ) && $fprops[FLD_REQUIRED] )
                $rls[] = 'required';
            else 
                $rls[] = 'nullable';

            if( isset( $fprops[FLD_DATA_TYPE]) ){
                switch($fprops[FLD_DATA_TYPE]){
                    case DT_INT:
                    case DT_FLOAT:
                    case DT_BOOLEAN:
                        $rls[] = 'numeric';
                    case DT_TEXT:
                    case DT_DATE:
                        $rls[] = 'max:255';
                }
            }  

            // $rules[$fname] = implode("|", $rls);
            $rules[$fname] = $rls;
        }

        return $rules;
    }

}