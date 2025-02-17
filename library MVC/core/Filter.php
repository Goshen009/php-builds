<?php
namespace Core;

use PDO;
use Core\Database;

class Filter {
    public static function filter(PDO $db, array $inputs, array $filters, array $messages=[]) : array {
        $sanitation_filters = [];
        $validation_filters = [];
    
        foreach ($filters as $field_name=>$value) {
            $sanitation_filters[$field_name] = array_splice($value, 0, 1)[0]; // this removes the first element from the array of strings because the first element is always the filter to sanitize
            $validation_filters[$field_name] = $value; // sets the rest of the array to be the validation filters
        }

        $sanitized_inputs = Sanitation::sanitize($inputs, $sanitation_filters);
        $errors = Validation::validate($db, $sanitized_inputs, $validation_filters, $messages);

        return [$sanitized_inputs, $errors];
    }

    public static function filter_image(PDO $db, array $inputs, array $filters): array {
        $inputs = [];
        $errors = [];

        foreach ($filters as $field_name=>$value) {
            if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] === 0) {
                $target_dir = __DIR__ . '/../public/uploads/';
                $file_name = basename($_FILES[$field_name]['name']);
                
                $target_file_path = $target_dir . $file_name;
                $file_type = strtolower(pathinfo($target_file_path, PATHINFO_EXTENSION));

                if (filesize($_FILES[$field_name]['tmp_name']) > $value['max-size']) {
                    $errors[$field_name] = 'File is bigger than the expected filesize.';
                    continue;
                }

                if (in_array($file_type, $value['allowed-types'])) {
                    $result = move_uploaded_file($_FILES[$field_name]['tmp_name'], $target_file_path);
                    if ($result === false) {
                        $errors[$field_name] = 'An error occured. Upload the file again.';
                    } else {
                        $inputs[$field_name] = '/../uploads/' . $file_name;
                    }
                } else {
                    $errors[$field_name] = 'It is not the expected file type.';
                }
            } else {
                $errors[$field_name] = "No file was uploaded";
            }
        }

        return [$inputs, $errors];
    }
}


class Sanitation {
    const FILTERS = [
        'string' => FILTER_SANITIZE_SPECIAL_CHARS,
        'string[]' => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
            'flags' => FILTER_REQUIRE_ARRAY
        ],
        'email' => FILTER_SANITIZE_EMAIL,
        'int' => [
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'flags' => FILTER_REQUIRE_SCALAR,
        ],
        'int[]' => [
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'flags' => FILTER_REQUIRE_ARRAY,
        ],
        'float' => [
            'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
            'flags' => FILTER_FLAG_ALLOW_FRACTION,
        ],
        'float[]' => [
            'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
            'flags' => FILTER_REQUIRE_ARRAY,
        ],
        'url' => FILTER_SANITIZE_URL
    ];

    public static function trim_array(array $items) : array {
        return array_map(function ($item) {
            if (is_string($item)) { return trim($item); }
            else if (is_array($item)) { return $this->trim_array($item); }
            else { return $item; }
        }, $items);
    }

    public static function sanitize(array $inputs, array $filters, bool $trim=true) : array { // if there are errors in the input, it'll return null
        array_walk($filters, fn(&$value) => $value = static::FILTERS[$value]); // gets the sanitation filter
    
        $filtered_data = filter_var_array($inputs, $filters);
    
        if ($trim) { return static::trim_array($filtered_data); }
        else { return $filtered_data; }
    }
}


class Validation {
    const DEFAULT_VALIDATION_ERRORS = [
        'required' => 'Please enter the %s',
        'email' => 'The %s is not a valid email address',
        'number' => 'The %s is not a numeric value',
        'min' => 'The %s must be at least %s',
        'max' => 'The %s must be at most %s',
        'between' => 'The %s must have between %d and %d characters',
        'same' => 'The %s must match with %s',
        'alphanumeric' => 'The %s should have only letters and numbers',
        'secure' => 'The %s must have between 8 and 64 characters and contain at least one number, one upper case letter, and one lower case letter, and one special character',
        'unique' => 'The %s already exists',
        'changed' => 'The %s already exists',
        'confirm-password' => [
            'required' => 'Please enter the password again',
            'same' => 'The passwords do not match'
        ],
    ];
    
    public static function split_rule(string $rule, PDO $db) : array {
        if (strpos($rule, ':')) { // does the rule have parameters?
            [$rule_name, $params] = array_map('trim', explode(':', $rule));
            $rule_parameters = array_map('trim', explode(',', $params));
        } else {
            $rule_name = trim($rule);
            $rule_parameters = [];
        }

        if ($rule_name === 'unique' || $rule_name === 'exists' || $rule_name == 'changed') {
            $rule_parameters[] = $db; // those two checks require a PDO. that's what is being added here.
        }
    
        return [$rule_name, $rule_parameters];
    }

    public static function validate(PDO $db, array $inputs, array $filters, array $messages=[]) : array {
        $validation_errors = array_merge(static::DEFAULT_VALIDATION_ERRORS, $messages); // you can pass in error messages for some specific fields instead of the default ones.

        $errors = [];

        foreach ($filters as $filter=>$rules) { // filter : 'name', rules : 'required', 'alphanumeric'
            foreach ($rules as $rule) {
                [$rule_name, $rule_parameters] = static::split_rule($rule, $db);

                $rule_function = "is_" . $rule_name;

                if (is_callable([static::class, $rule_function])) {
                    $is_rule_passed = static::$rule_function($inputs, $filter, ...$rule_parameters);

                    if (!$is_rule_passed) {
                        $errors[$filter] = sprintf(
                            $validation_errors[$filter][$rule_name] ?? $validation_errors[$rule_name],
                            $filter,
                            ...$rule_parameters
                        );
                    }
                }
            }
        }

        return $errors;
    }

    public static function is_required(array $inputs, string $filter) : bool {
        return isset($inputs[$filter]) && trim($inputs[$filter]) !== '';
    }
    
    public static function is_email(array $inputs, string $filter) : bool {
        return filter_var($inputs[$filter], FILTER_VALIDATE_EMAIL);
    }
    
    public static function is_min(array $inputs, string $filter, int $min) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
        return $inputs[$filter] >= $min;
    }
    
    public static function is_max(array $inputs, string $filter, int $min) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
        return $inputs[$filter] <= $min;
    }
    
    public static function is_between(array $inputs, string $filter, int $min, int $max) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
    
        $len = mb_strlen($inputs[$filter]);
        return $len >= $min && $len <= $max;
    }
    
    public static function is_same(array $inputs, string $filter, string $other) : bool {
        if (isset($inputs[$filter]) && isset($inputs[$other])) {
            return $inputs[$filter] === $inputs[$other];
        }
        return false;
    }
    
    public static function is_alphanumeric(array $inputs, string $filter) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
    
        $input_without_space = str_replace(" ", "", $inputs[$filter]);
        return ctype_alnum($input_without_space);
    }
    
    public static function is_secure(array $inputs, string $filter) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
    
        $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
        return preg_match($pattern, $inputs[$filter]);
    }
    
    public static function is_unique(array $inputs, string $filter, string $table, string $column, PDO $db) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
    
        $sql = "SELECT $column FROM $table WHERE $column =:value";
    
        $statement = $db->prepare($sql);
        $statement->bindValue(":value", $inputs[$filter]);
        
        $statement->execute();
        return $statement->fetchColumn() === false;
    }

    public static function is_changed(array $inputs, string $filter, $former_value, string $table, string $column, PDO $db): bool {
        if (!isset($inputs[$filter])) {
            return false;
        }

        if ($inputs[$filter] === $former_value) {
            return true;
        }

        // if the value is not changed, simply return a true
        // if the value is changed, make sure that it does not already exists in the table.
        return static::is_unique($inputs, $filter, $table, $column, $db);
    }
}

?>