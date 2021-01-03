<?php


if( !isset($stdFields) ){
	return ' field structure error ';
}

// mus be an array
if( !is_array($stdFields) ) {
	return ' field structure error ';
}

// no fields provided, than return empty
if( count($stdFields) == 0 ) {
	return '';
}

$op = isset($op) ? $op : 'create';

if( $op == 'edit') {
	if( !isset($mainModel) )
		return '';
}

$result = array();
// start page generation
foreach( $stdFields as $name => $field ) {

	$_LABEL = isset( $field[FLD_LABEL] ) ? $field[FLD_LABEL] : $name;
	$_REQUIRED = ( isset( $field[FLD_REQUIRED] ) &&  $field[FLD_REQUIRED] ) ? true : false;
	$_PLACEHOLDER = ( isset( $field[FLD_PLACEHOLDER] ) && !empty($field[FLD_PLACEHOLDER]) ) 
					? $field[FLD_PLACEHOLDER] 
					: '';

	$_READONLY = ( isset( $field[FLD_READ_ONLY]) && $field[FLD_READ_ONLY] )
				? true
				: false;

	$_OPTIONS = ( isset( $field[FLD_OPTIONS]) && !empty($field[FLD_OPTIONS])) 
				? $field[FLD_OPTIONS] 
				: [];
    
    $errorTpl = config('laravel-hfm.ui.standardForm.deafultFieldErrorTpl');
	$_FIELDERROR_HTML =	( isset($errors) && $errors->has($name) != null  ) 
					? str_replace("%_ERROR$", $errors->first($name) , $errorTpl) 
					: '';
	
    // shows a extra message on what should be the value of the field
    $fieldInfoTpl = config('laravel-hfm.ui.standardForm.defaultFieldInfoTpl');
	$_INPUTHELPERMSG_HTML = ( isset( $field[FLD_HELPER_MSG] ) && !empty($field[FLD_HELPER_MSG])  )
						? str_replace("%_MSG%", $field[FLD_HELPER_MSG], $fieldInfoTpl)
						: '';

	$_INPUTGROUP_EXTRA_CLASS = isset( $field[FLD_INPUTGROUP_EXTRA_CLASS] )  && !empty($field[FLD_INPUTGROUP_EXTRA_CLASS] ) 
						? $field[FLD_INPUTGROUP_EXTRA_CLASS]
						: config('laravel-hfm.ui.standardForm.defaultInputGroupClasses');

	$_CMPROWS = ( isset( $field[FLD_ROWS] )  && is_numeric( $field[FLD_ROWS] ) && $field[FLD_ROWS] > 0 ) 
				? $field[FLD_ROWS]
				: '1';

	//
	// START get the value for the current field 
	//
	$_VAL = null;

	if( isset( $request ) && isset($request->$name) ){
		// fills the field with the old value if $request->validation error occures
		$_VAL = $request->$name;    
	}

	if( $op == 'edit' ){
		if( isset($mainModel->$name) ) {
			// get the value from the db result 
			$_VAL = $mainModel->$name;
		}
	
		if( $field[FLD_DATA_TYPE] == DT_DATE && $_VAL instanceof \Carbon\Carbon ) {
			$_VAL = $_VAL->format('d/m/Y');
		}
	}

	if( $_VAL == null ) {
		$_VAL = isset( $field[FLD_DEFAULT_VALUE]) 
				? isset( $field[FLD_DEFAULT_VALUE]) 
				: null;
	}

	//
	// END get the value for the current field 
	//

    $inputClass = config('laravel-hfm.ui.standardForm.defaultInputClass');
	$_PARAMS = [
        FLD_INPUT_CLASS => $inputClass ,
        FLD_PLACEHOLDER => $_PLACEHOLDER
	]; 
	
	//
	if($_READONLY ){
		$_PARAMS['readonly'] = true;
		$_PARAMS[FLD_INPUT_CLASS] = $_PARAMS[FLD_INPUT_CLASS] . ' readOnly';
	}

	$requiredMark = '';
	if($_REQUIRED) {
        $requiredMark = config('laravel-hfm.ui.standardForm.defaultRequiredMark');
        $_LABEL = $_LABEL . ' '. $requiredMark; 
		$_PARAMS[FLD_REQUIRED] = true;
	}
    
    $labelTpl = config('laravel-hfm.ui.standardForm.dafaultLabelTpl');
    $labelClasses = config('laravel-hfm.ui.standardForm.dafaultLabelClasses');

	$_fld_label_html = str_replace( [ "%_NAME%", "%_CLASS%" ,  "%_LABEL%" ], [ $name, $labelClasses, $_LABEL ], $labelTpl );
	
	//
	// START component composition
    //
    $inputGroupTpl = config('laravel-hfm.ui.standardForm.defaultInputGroupTpl');
    $inputGroupTpl = str_replace("%_CLASSES%", $_INPUTGROUP_EXTRA_CLASS, $inputGroupTpl );

	$inputGroupElements = $_fld_label_html;

	switch( $field[FLD_UI_CMP] ) {
		case CMP_NUMBER:
			$inputGroupElements .= Form::number($name, $_VAL, $_PARAMS );
			break;

		case CMP_TEXT:
			if( $field[FLD_DATA_TYPE] == DT_DATE ){
				// datepicker must be set only if FLD_UI_CMP is text
				$_PARAMS[FLD_INPUT_CLASS] = $_PARAMS[FLD_INPUT_CLASS] . ' datepicker';
			}

			$inputGroupElements .= Form::text($name, $_VAL, $_PARAMS);
			break;

		case CMP_EMAIL:
			$inputGroupElements .= Form::text($name, $_VAL, $_PARAMS);
			break;

		case CMP_PASSWORD:
			
			$inputGroupElements .= Form::password($name, $_PARAMS);
			break;

		case CMP_CHECKBOX:
			$_PARAMS[FLD_INPUT_CLASS] = ' form-check ';
			$cssReadOnly = $_READONLY ? ' readOnly ': '';

			// $inputGroupElements .= Form::hidden($name,0); 
			// $inputGroupElements .= Form::checkbox($name, 1, $_VAL, $_PARAMS );
			
			// 
			$inputGroupElements .= '<div class="custom-control custom-checkbox " style="padding-top:.35rem;">'.
								'<input type="hidden" class="custom-control-input  '.$cssReadOnly.'" value="0">'.
								'<input type="checkbox" class="custom-control-input '.$cssReadOnly.' " '.( $_VAL ? 'checked="checked"': '' ).' value="1" name="'.$name.'" id="'.$name.'">'.
								'<label class="custom-control-label  '.$cssReadOnly.'" for="'.$name.'"></label>'.
							'</div>';

			break;

		case CMP_SELECT:
			        
			$_PARAMS[FLD_PLACEHOLDER] = empty($_PLACEHOLDER) ? '-- Seleziona '.$_LABEL .' --' : $_PLACEHOLDER;
			$inputGroupElements .= Form::select($name, $_OPTIONS ,$_VAL, $_PARAMS);
			break;

		case CMP_TEXT_AREA:
			$_PARAMS[FLD_ROWS] = $_CMPROWS;
			$inputGroupElements .= Form::textarea($name, $_VAL, $_PARAMS);
			
			break;

		default:
			break;

	}

    $inputGroupElements .= $_INPUTHELPERMSG_HTML . $_FIELDERROR_HTML;
    $inputGroup = str_replace("%_ELEMETNS%", $inputGroupElements, $inputGroupTpl );

	//					
	// END component composition
	//

	// add field to form
	$result[] = $inputGroup;

} // end foreach


// Print the complete HTML code 
$inputGroups = implode(' ' , $result);

$wrapperTpl = config('laravel-hfm.ui.standardForm.defaultInputGroupsWrapperTpl');
$formInputs = str_replace("%_INPUTGROUPS%", $inputGroups, $wrapperTpl);
echo $formInputs;

?>
