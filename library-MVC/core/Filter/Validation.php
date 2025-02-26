<?php

namespace Core\Filter;

use PDO;

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
    
    public function split_rule(string $rule, PDO $db) : array {
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

    public function validate(PDO $db, array $inputs, array $filters, array $messages=[]) : array {
        $validation_errors = array_merge(self::DEFAULT_VALIDATION_ERRORS, $messages); // you can pass in error messages for some specific fields instead of the default ones.

        $errors = [];

        foreach ($filters as $filter=>$rules) { // filter : 'name', rules : 'required', 'alphanumeric'
            foreach ($rules as $rule) {
                [$rule_name, $rule_parameters] = $this->split_rule($rule, $db);

                $rule_function = "is_" . $rule_name;

                if (is_callable([$this, $rule_function])) {
                    $is_rule_passed = $this->$rule_function($inputs, $filter, ...$rule_parameters);

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

    public function is_required(array $inputs, string $filter) : bool {
        return isset($inputs[$filter]) && trim($inputs[$filter]) !== '';
    }
    
    public function is_email(array $inputs, string $filter) : bool {
        return filter_var($inputs[$filter], FILTER_VALIDATE_EMAIL);
    }
    
    public function is_min(array $inputs, string $filter, int $min) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
        return $inputs[$filter] >= $min;
    }
    
    public function is_max(array $inputs, string $filter, int $min) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
        return $inputs[$filter] <= $min;
    }
    
    public function is_between(array $inputs, string $filter, int $min, int $max) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
    
        $len = mb_strlen($inputs[$filter]);
        return $len >= $min && $len <= $max;
    }
    
    public function is_same(array $inputs, string $filter, string $other) : bool {
        if (isset($inputs[$filter]) && isset($inputs[$other])) {
            return $inputs[$filter] === $inputs[$other];
        }
        return false;
    }
    
    public function is_alphanumeric(array $inputs, string $filter) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
    
        $input_without_space = str_replace(" ", "", $inputs[$filter]);
        return ctype_alnum($input_without_space);
    }
    
    public function is_secure(array $inputs, string $filter) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
    
        $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
        return preg_match($pattern, $inputs[$filter]);
    }
    
    public function is_unique(array $inputs, string $filter, string $table, string $column, PDO $db) : bool {
        if (!isset($inputs[$filter])) {
            return false;
        }
    
        $sql = "SELECT $column FROM $table WHERE $column =:value";
    
        $statement = $db->prepare($sql);
        $statement->bindValue(":value", $inputs[$filter]);
        
        $statement->execute();
        return $statement->fetchColumn() === false;
    }

    public function is_changed(array $inputs, string $filter, $former_value, string $table, string $column, PDO $db): bool {
        if (!isset($inputs[$filter])) {
            return false;
        }

        if ($inputs[$filter] === $former_value) {
            return true;
        }

        // if the value is not changed, simply return a true
        // if the value is changed, make sure that it does not already exists in the table.
        return $this->is_unique($inputs, $filter, $table, $column, $db);
    }
}