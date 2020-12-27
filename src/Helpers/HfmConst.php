<?php

namespace AnatolieGhebea\LaravelHfm\Helpers;

/**
 * CONST FOR FIELD PROP cmpType;
 */
define('CMP_TEXT', 'text');
define('CMP_TEXT_AREA', 'textarea');
define('CMP_NUMBER', 'number');
define('CMP_SELECT', 'select');
define('CMP_CHECKBOX', 'checkbox');
define('CMP_HIDDEN', 'hidden');


/**
 * CONST FOR FIELD PROP $dataType
 */
define('DT_INT', 'int');
define('DT_FLOAT', 'float');
define('DT_BOOLEAN', 'boolean');
define('DT_DATE', 'date');
define('DT_TEXT', 'text');
define('DT_TEXT_AREA', 'textarea');


/**
 * STANDARD FIELDS PROPS
 */
define('FLD_LABEL', 'label');
define('FLD_UI_CMP', 'cmpType');
define('FLD_DATA_TYPE', 'dataType');
define('FLD_PRIMARY', 'primary');
define('FLD_REQUIRED', 'required');
define('FLD_LENGTH', 'length');


/**
 * EXCEL
 */
define('EXCEL_LABEL', 'label');
define('EXCEL_JOIN_FIELD_VALUE', 'join_field');
define('EXCEL_BOOLEAN_TYPE', 'boolean');
define('EXCEL_MODEL_FUNCTION', 'model_function');


// HELPER CLASS
// creating this helper class to keep the custom data types consistent acrosso all the application files
class HfmConst
{

    // CONST DOR FIELD $cmpType
    const CMP_TEXT = 'text';
    const CMP_TEXT_AREA = 'textarea';
    const CMP_NUMBER = 'number';
    const CMP_SELECT = 'select';
    const CMP_CHECKBOX = 'checkbox';
    const CMP_HIDDEN = 'hidden';
    

    // CONST FOR FIELD $dataType
    const DT_INT = 'int';
    const DT_FLOAT = 'float';
    const DT_BOOLEAN = 'boolean';
    const DT_DATE = 'date';
    const DT_TEXT = 'text';
    const DT_TEXT_AREA = 'textarea';

    // CONST DOR FIELD $fltType
    const FLT_DEFAULT = 'default';
    const FLT_RANGE = 'range';
}
