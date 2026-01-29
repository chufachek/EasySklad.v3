<?php
class Validator
{
    public static function required($fields, $data)
    {
        $errors = array();
        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $errors[$field] = 'Field is required';
            }
        }
        return $errors;
    }
}
