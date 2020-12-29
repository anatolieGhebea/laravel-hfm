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


// start page generation
foreach( $stdFilters as $name => $field ) {

    $requestVal = ( isset( $request ) && isset($request->$name) ) ? $request->$name : null;

    $stdOpts = array( 'class' => 'form-control rounded-0' );

    $filedLabel =  isset( $field['label'] ) ? $field['label'] : $name;
    $placeholder = (isset( $field['placeholder']) && !empty($field['placeholder'])) ? $field['placeholder'] : '';

    // $filedLabelHtml = '<label for="'.$name.'" >'.$filedLabel.' </label>' ;

    // $inputFiled = '<div class="form-group">'.
    //                 $filedLabelHtml;

    $inputFiled = '<div class="col-4 form-group">';

    switch( $field['cmpType'] ) {
        case CMP_NUMBER:

            $inputFiled .= Form::number($name, $requestVal, $stdOpts );

            break;

        case CMP_TEXT:
        case CMP_TEXT_AREA:

            $stdOpts['placeholder'] = $filedLabel;
            if( $field['dataType'] == DT_DATE ){
              $stdOpts['class'] = $stdOpts['class'] . ' datepicker';
            }

            $inputFiled .= Form::text($name, $requestVal, $stdOpts);

            break;

        case CMP_CHECKBOX:
            $stdOpts['class'] = ' form-check ';
            // $inputFiled .=	Form::hidden($name,0); // serve per poter gestire il valore 0 del check
            $inputFiled .= Form::checkbox($name, 1, $requestVal, $stdOpts );

            break;

        case CMP_SELECT:

            $options = (isset( $field['options']) && !empty($field['options'])) ? $field['options'] :  [];
            $stdOpts['placeholder'] = empty($placeholder) ? 'Seleziona '.$filedLabel : $placeholder;
            $inputFiled .= Form::select($name, $options ,$requestVal, $stdOpts);
            break;

        default:
            break;

    }

    $inputFiled .= '</div>';

    $result[] = $inputFiled;

} // end foreach

$res = '';
$inputFileds = implode(' ' , $result);


$res = '<div class="row">'. $inputFileds .'</div>';

echo $res;

?>
