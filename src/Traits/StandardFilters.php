<?php

namespace Ghebby\LaravelHfm\Traits;

trait StandardFilters 
{

    /**
	 * This method composes the query by applying the $data coming form the Client on 
     * the $stdFields definition.
     * 
     * @param Illuminate\Database\Eloquent\Model::query     $query  The elequent\Model::query() on which to add all the conditions
     * @param Array     $stdFields  The list of the fields defined in the 'getFieldMap'
     * @param Array     $data       The values coming from the client
     * 
     * @return Array    $result     [
     *                                  'query' => $query,
     *                                  'appaliedFilters' => true | false,
     *                                  'err' => 'string with error'
     *                              ]
	 */
	public static function applayStandardFilters( $query, $stdFields, $data ) {
        
        $appaliedFilters = false;
        $err = null;

		if(empty($query) || $query == null ){
            return [
                'query'=> $query, 
                'appaliedFilters' => $appaliedFilters,  
                'err'=> '$query non set' 
            ];
		}

		if(!is_array($stdFields)){
            return [
                'query'=> $query, 
                'appaliedFilters' => $appaliedFilters,  
                'err'=> '$stdFields must be an array'
            ];
		}

        if( empty($data) ){
            // nothing to do, return ok
            return [
                'query'=> $query, 
                'appaliedFilters' => $appaliedFilters, // no filter applied, stays false 
                'err'=> null
            ];
        }
		
        /* Manage filters */
        foreach ($stdFields as $key => $fltProp) {
            
            // empty(intval('0')) evaluates to true, so to accept 0 (zero) as valid integr for query
            // a manual check is required 
            if( isset($data[$key]) && ( intval( $data[$key] ) == 0  || !empty($data[$key]) ) ){

                $fltVal = $data[$key];
                if( $fltProp[FLD_DATA_TYPE] == DT_DATE ){
                    $uiFormat = config('laravel-hfm.formats.date_ui');
                    $dbFormat = config('laravel-hfm.formats.date_db');
                    $fltVal = \Carbon\Carbon::createFromFormat($uiFormat, $fltVal )->format($dbFormat);
                } else if( $fltProp[FLD_DATA_TYPE] == DT_DATE_TIME ) {
                    $uiFormat = config('laravel-hfm.formats.datetime_ui');
                    $dbFormat = config('laravel-hfm.formats.datetime_db');
                    $fltVal = \Carbon\Carbon::createFromFormat($uiFormat, $fltVal )->format($dbFormat);
                }

                $cond = '=';
                if( isset($fltProp[FLD_FLT_COND]) ){
                    $cond = strtolower( $fltProp[FLD_FLT_COND] );
                    $cond = self::isValidCondition($cond) ? $cond : '=' ;
                }

                if( is_array($fltVal) && $cond != 'in' ){
                    return [
                        'query'=> $query, 
                        'appaliedFilters' => $appaliedFilters, 
                        'err'=> "Filter condition for field {$key} is not {in}, therefor the value must NOT be an array."
                    ];
                }

                switch ( $cond ) {
                    case '=':
                        $query->where($key, '=', $fltVal);
                        break;
                    case '<>':
                        $query->where($key, '<>', $fltVal);
                        break;
                    case 'like':
                        $query->where($key, 'LIKE', '%' . $fltVal . '%');
                        break;
                    case 'in':
                        $fltVal = !is_array($fltVal) ? [ $fltVal ]: $fltVal;
                        $query->whereIn($key, $fltVal);
                        break;
                    default:
                        // error
                        break;
                }
            
                // the first value that set a where condition on the query, 
                // indicates that at least one filter was applied
                $appaliedFilters = true;
            }
        
        }
        
		return ['query'=> $query, 'appaliedFilters' => $appaliedFilters,  'err'=> $err ];
	}

    /**
     * Determins if the given condition is valid 
     * @param String $cond
     * 
     * @return Boolean $res true|false
     */
    protected static function isValidCondition( $cond ) {
        $validConditions = ['=', 'like', '<>', 'in'];
        return in_array( $cond, $validConditions );
    }

}