<?php

namespace AnatolieGhebea\LaravelHfm\Helpers;

/**
 * CONST FOR FIELD PROP cmpType;
 */
define('CMP_TEXT', 'text'); // input of type text
define('CMP_TEXT_AREA', 'textarea'); // textarea
define('CMP_NUMBER', 'number'); // input of type number
define('CMP_EMAIL', 'number'); // input of type email
define('CMP_PASSWORD', 'number'); // input of type password
define('CMP_SELECT', 'select'); // select options
define('CMP_CHECKBOX', 'checkbox'); // input type checkbox
define('CMP_HIDDEN', 'hidden'); // input type text and hidden

/**
 * CONST FOR FIELD PROP $dataType
 */
define('DT_INT', 'int'); // type Integer
define('DT_FLOAT', 'float'); // type Float/Double
define('DT_BOOLEAN', 'boolean'); // type TinyInteger 
define('DT_DATE', 'date'); // type Date
define('DT_DATE_TIME', 'datetime'); // type DateTime
define('DT_TEXT', 'text'); // type Varchar(255)
define('DT_TEXT_AREA', 'textarea'); // type LongText

/**
 * STANDARD FIELDS PROPS
 */
define('FLD_LABEL', 'label');
define('FLD_UI_CMP', 'cmpType'); // the HTML component to use in the UI 
define('FLD_DATA_TYPE', 'dataType'); // the type of the field in the DataBase table. Information used to generate validation rules
define('FLD_PRIMARY', 'primary'); // indicates if the field is primary
define('FLD_REQUIRED', 'required'); // indicates if the field is required
define('FLD_LENGTH', 'length'); 
define('FLD_FLT_COND', 'fltCond'); 
define('FLD_OPTIONS', 'options'); 
define('FLD_PLACEHOLDER', 'placeholder'); 
define('FLD_READ_ONLY', 'readOnly'); 
define('FLD_HELPER_MSG', 'inputHelperMessage'); 
define('FLD_INPUTGROUP_EXTRA_CLASS', 'inputGroupClass'); 
define('FLD_INPUT_CLASS', 'class'); 
define('FLD_ROWS', 'rows'); // for CMP_TEXT_AREA
define('FLD_DEFAULT_VALUE', 'defaultValue'); // for CMP_TEXT_AREA

/**
 * EXCEL
 */
define('EXCEL_LABEL', 'label');
define('EXCEL_JOIN_FIELD_VALUE', 'join_field');
define('EXCEL_BOOLEAN_TYPE', 'boolean');
define('EXCEL_MODEL_FUNCTION', 'model_function');


/**
 * FIELD MAP 
 */
define('FM_KEEP', 'keep'); 
define('FM_DROP', 'drop');