<?php

$result = array();

// Variables to be specified from the caller
// $stdFields;

if( !isset($stdFilters) ){
    return ' field structure error ';
}

// mus be an array
if( !is_array($stdFilters) ) {
    return ' field structure error ';
}

// no fields provided, than return empty
if( count($stdFilters) == 0 ) {
    return '';
}


$showLabels = config('laravel-hfm.ui.standardFilters.showLabels');
if( isset($forceShowLabels) ){
    // if the developer choise has the precedence
    $showLabels = $forceShowLabels;
}

// start page generation
foreach( $stdFilters as $name => $field ) {

    $_LABEL = isset( $field[FLD_LABEL] ) ? $field[FLD_LABEL] : $name;
    $_PLACEHOLDER = ( isset( $field[FLD_PLACEHOLDER] ) && !empty($field[FLD_PLACEHOLDER]) ) ? $field[FLD_PLACEHOLDER] : '';

    if( !$showLabels ) {
        // if labels are hidden and the placeholder is empty, then show the label as placeholder.
        $_PLACEHOLDER = empty( $_PLACEHOLDER ) ? $name : $_PLACEHOLDER;
    }

    $_OPTIONS = ( isset( $field[FLD_OPTIONS]) && !empty($field[FLD_OPTIONS]) )  ? $field[FLD_OPTIONS] : [];

	$_VAL = null;
	if( isset( $request ) && isset($request->$name) ){
		// fills the field with the old value if $request->validation error occures
		$_VAL = $request->$name;    
	}

    // shows a extra message on what should be the value of the field
	$_INPUTHELPERMSG_HTML = ( isset( $field[FLD_HELPER_MSG] ) && !empty($field[FLD_HELPER_MSG])  )
                            ? '<div><small class="text-muted">'. $field[FLD_HELPER_MSG] . '</small></div>' 
                            : '';

    $_CMP_WRAPPER_CLASS = isset( $field[FLD_INPUTGROUP_EXTRA_CLASS] )  && !empty($field[FLD_INPUTGROUP_EXTRA_CLASS] ) ? $field[FLD_INPUTGROUP_EXTRA_CLASS] : ' col-md-4 ';


    $_PARAMS = [ 
        'class' => ' form-control rounded-0 ',
        'placeholder' => $_PLACEHOLDER
    ];
    

    $_fld_label_html = '' ;
    if( $showLabels ) {
        $_fld_label_html = '<label for="'.$name.'" class="">' . $_LABEL . ' </label>' ;
    } 

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
        case CMP_TEXT_AREA:

            if( $field[ DT_DATE ] == DT_DATE ){
              $_PARAMS['class'] = $_PARAMS['class'] . ' datepicker';
            }

            $inputFiled .= Form::text($name, $_VAL, $_PARAMS);

            break;

        case CMP_CHECKBOX:
            $_PARAMS['class'] = ' text-danger ';
            // $inputFiled .=	Form::hidden($name,0); 
            // $inputFiled .= Form::checkbox($name, 1, $requestVal, $stdOpts );
            $inputFiled .= ' -- please use a Select component type with yes/no options.';

            break;

        case CMP_SELECT:

            $_PARAMS['placeholder'] = empty($_PLACEHOLDER) ? 'Seleziona '.$_LABEL : $_PLACEHOLDER;
            $inputFiled .= Form::select($name, $_OPTIONS ,$_VAL, $_PARAMS);
            break;

        default:
            break;

    }

    $inputFiled .= $_INPUTHELPERMSG_HTML .
                '</div>';

    $result[] = $inputFiled;

} // end foreach

$res = '';
$inputFileds = implode(' ' , $result);
$res = '<div class="row">'. $inputFileds .'</div>';

echo $res;

?>
