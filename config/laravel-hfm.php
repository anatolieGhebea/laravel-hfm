<?php

return [

    /*
    |--------------------------------------------------------------------------
    | laravel-hfm
    |--------------------------------------------------------------------------
    | 
    | Configuration varaibles
    | 
    */

    /*
    |--------------------------------------------------------------------------
    | Formats
    |--------------------------------------------------------------------------
    | 
    | Default formats 
    */
    'formats' => [
        'date_db' => 'Y-m-d',
        'date_ui' => 'd/m/Y',
        'datetime_db' => 'Y-m-d H:i:s',
        'datetime_ui' => 'd-m-Y H:i:s'
    ],


    'ui' => [

        /*
        |--------------------------------------------------------------------------
        | UI helper for StandardForm
        |--------------------------------------------------------------------------
        | 
        | This value set some of the defaults variavles inside the "standardForm" 
        | helper function.
        |
        | WARNING: the placeholders names MUST be preserved! A placeholder begins and ends with %, 
        | %_NAME% , %_CLASS% are examples of place holders.
        |
        */
        

        /*
        |--------------------------------------------------------------------------
        | HTML Output 
        |--------------------------------------------------------------------------
        | 
        | this is the default configuration uses class names of the framework bootstrap ^4.*
        | 
        | A tipical HTML output following this rules:
        | 
        | 	<div class="row"> <!-- defaultInputGroupsWrapperTpl -->
        |       <div class="form-group col-md-6 ">  <!-- defaultInputGroupTpl -->
        |           <label for="name" class="">Name <span class="text-danger"> *</span></label> <!-- dafaultLabelTpl -->
        |           <input class=" form-control " placeholder="" required="" name="name" type="text">
        |           <div><small class="text-muted">Insert the short name</small></div>
        |       </div>
        |       <div class="form-group  col-md-6 ">
        |           <label for="description" class="">Description</label>
        |           <textarea class=" form-control " placeholder="" rows="1" name="description" cols="50"></textarea>
        |       </div>
        |       <div class="form-group  col-md-6 ">
        |           <label for="email" class="">E-mail <span class="text-danger"> *</span></label>
        |           <input class=" form-control " placeholder="" required="" name="email" type="text">
        |           <div class="text-danger">Field email is required!</div>
        |       </div>
        |   </div>
        | 
        */
        'standardForm' => [

            // set attribute 'required' on the input field.
            // if the value it is set to false, the required constraint will be 
            // checked only on the server side, no restriction on the client side
            // That means that a submit will go through with an empty reqired value, and must be blocked by the server
            'addRequiredAttribute' => true,
            
            // label template 
            'dafaultLabelTpl' => '<label for="%_NAME%" class="%_CLASS%">%_LABEL%</label>',
            'dafaultLabelClasses' => '',

            // required mark
            'defaultRequiredMark' => '<span class="text-danger"> *</span>',

            // field has error 
            'defaultHasErrorClass' => ' is-invalid', 
            'deafultFieldErrorTpl' => '<div class="text-danger">%_ERROR%</div>',

            // info message displayd under the input field
            'defaultFieldInfoTpl' => '<div><small class="text-muted">%_MSG%</small></div>', 

            // the wrapper tpl for all the input fields
            'defaultInputGroupsWrapperTpl' => '<div class="row">%_INPUTGROUPS%</div>', 

            // default class for a input group 
            'defaultInputGroupTpl' => '<div class="form-group %_CLASSES% ">%_ELEMETNS%</div>',

            // default class for a input group  class
            'defaultInputGroupClasses' => ' col-md-6 ',

            // default class for a input
            // extra classes and params for generating the html
            // for string provided in $getFieldMaps array and correspond
            // to those of the class 'Form' => Collective\Html\FormFacade::class,
            'defaultInputClass' => ' form-control ',
        ], 

        'standardFilters' => [
            // show compnent labels 
            'showLabels' => true,
        ]
    ]




];
