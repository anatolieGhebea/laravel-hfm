<?php

namespace Ghebby\LaravelHfm\Contracts;

interface FieldsMapContract
{

    /**
     * WARNING
     * This package does not support models with multiple primary keys.
     *
     */

    /**
     * This method must return the Model fields with their properties.
     * @param Array     $opts   An array of options, in case a customization of the bihavior is needed
     * @return Array    $fields
     *
     * $fields = [
     *     'db_field_name' => [
     *         FLD_LABEL => 'The label to be shown on the browser',
     *         FLD_UI_CMP => 'The html element to be used on the browser',
     *         FLD_DATA_TYPE => 'The filed data type on the db table',
     *         FLD_PRIMARY => '1|0 indicates if the field is the primary key. (if primary is 1 then FLD_REQUIRED must be 0)',
     *         FLD_REQUIRED => '1|0 indicates if value is required for the field',
     *         FLD_LENGTH => '-1 ( for long text, float ) | max 255 (for varchar)| max 11 (for integer) | 1 ( boolean ) ',
     *         FLD_FLT_COND => ' = | LIKE  indicates which condition to use when constructing the query automaticaly'
     *         FLD_OPTIONS => 'when FLD_UI_CMP is of type CMP_SELECT is neccessari to provaide an arrya of options, defaults to []. Normaly is populate in the controller right before passing it to the view',
     *         FLD_PLACEHOLDER => 'the text for the placeholder, default value is empty ',
     *         FLD_READ_ONLY => '1 | 0 indicates if the field is editable or not',
     *         FLD_HELPER_MSG => 'A helper text that's shown under the input field',
     *         FLD_WRP_CLASS => 'Default:" col-md-6 ".  Classes to be applied to the input field wrapper div, mainly used to indicate the width of the field.',
     *         FLD_ROWS => 'Default: "1". indicates the number of rows for the text area.',
     *         FLD_DEFAULT_VALUE => 'used to preset a value on the field, if the value in DB is null else DB value has precedence',
     *     ]
     * ];
     *
    */
    public static function getFieldsMap();
}
