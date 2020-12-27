<?php

namespace AnatolieGhebea\LaravelHfm\Contracts;

interface FieldsMapContract
{
    /**
     * This method must return the Model fields with their properties.
    */
    public static function getFieldsMap($opts = []);
}
