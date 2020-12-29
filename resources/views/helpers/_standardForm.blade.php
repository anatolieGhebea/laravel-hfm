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
	
	$_FIELDERROR_HTML =	( isset($errors) && $errors->has($name) != null  ) 
					? '<div class="text-danger">'. $errors->first($name) . '</div>' 
					: '';
	
	// shows a extra message on what should be the value of the field
	$_INPUTHELPERMSG_HTML = ( isset( $field[FLD_HELPER_MSG] ) && !empty($field[FLD_HELPER_MSG])  )
						? '<div><small class="text-muted">'. $field[FLD_HELPER_MSG] . '</small></div>' 
						: '';

	$_CMP_WRAPPER_CLASS = isset( $field[FLD_WRP_CLASS] )  && !empty($field[FLD_WRP_CLASS] ) 
						? $field[FLD_WRP_CLASS]
						: ' col-md-6 ';

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
	
		if( $field['dataType'] == DT_DATE && $_VAL instanceof \Carbon\Carbon ) {
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

	$_PARAMS = [
		'class' => 'form-control  '
	]; 
	
	//
	if($_READONLY ){
		$_PARAMS['readonly'] = true;
		$_PARAMS['class'] = $_PARAMS['class'] . ' readOnly';
	}

	$requiredMark = '';
	if($_REQUIRED) {
		$requiredMark = '<span class="text-danger"> *</span>';
		$_PARAMS['required'] = true;
	}
	
	$_fld_label_html = '<label for="'.$name.'" class="">'.$_LABEL. $requiredMark.' </label>' ;

	//
	// START component composition
	//
	$inputFiled = '<div class="form-group '. $_CMP_WRAPPER_CLASS .'">'.
					$_fld_label_html;

	switch( $field[FLD_UI_CMP] ) {
		case CMP_NUMBER:
			$inputFiled .= Form::number($name, $_VAL, $_PARAMS );
			break;

		case CMP_TEXT:
			if( $field[FLD_DATA_TYPE] == DT_DATE ){
				// datepicker must be set only if FLD_UI_CMP is text
				$_PARAMS['class'] = $_PARAMS['class'] . ' datepicker';
			}

			$inputFiled .= Form::text($name, $_VAL, $_PARAMS);
			break;

		case CMP_EMAIL:
			$inputFiled .= Form::text($name, $_VAL, $_PARAMS);
			break;

		case CMP_PASSWORD:
			
			$inputFiled .= Form::password($name, $_PARAMS);
			break;

		case CMP_CHECKBOX:
			$_PARAMS['class'] = ' form-check ';
			$cssReadOnly = $_READONLY ? ' readOnly ': '';

			//$inputFiled .= Form::hidden($name,0); 
			//$inputFiled .= Form::checkbox($name, 1, $_VAL, $_PARAMS );
			
			// 
			$inputFiled .= '<div class="custom-control custom-checkbox " style="padding-top:.35rem;">'.
								'<input type="hidden" class="custom-control-input  '.$cssReadOnly.'" value="0">'.
								'<input type="checkbox" class="custom-control-input '.$cssReadOnly.' " '.( $_VAL ? 'checked="checked"': '' ).' value="1" name="'.$name.'" id="'.$name.'">'.
								'<label class="custom-control-label  '.$cssReadOnly.'" for="'.$name.'"></label>'.
							'</div>';

			break;

		case CMP_SELECT:
			        
			$_PARAMS['placeholder'] = empty($_PLACEHOLDER) ? '-- Seleziona '.$_LABEL .' --' : $_PLACEHOLDER;
			$inputFiled .= Form::select($name, $_OPTIONS ,$_VAL, $_PARAMS);
			break;

		case CMP_TEXT_AREA:
			$_PARAMS['rows'] = $_CMPROWS;
			$inputFiled .= Form::textarea($name, $_VAL, $_PARAMS);
			
			break;

		default:
			break;

	}

	$inputFiled .=      $_INPUTHELPERMSG_HTML .
						$_FIELDERROR_HTML .
					'</div>';
	//					
	// END component composition
	//

	// add field to form
	$result[] = $inputFiled;

} // end foreach


// Print the complete HTML code 
$inputFileds = implode(' ' , $result);
echo $inputFileds;

?>
