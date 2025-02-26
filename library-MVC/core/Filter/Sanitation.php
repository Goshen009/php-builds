<?php

namespace Core\Filter;

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

    public function trim_array(array $items) : array {
        return array_map(function ($item) {
            if (is_string($item)) { return trim($item); }
            else if (is_array($item)) { return $this->trim_array($item); }
            else { return $item; }
        }, $items);
    }

    public function sanitize(array $inputs, array $filters, bool $trim=true) : array { // if there are errors in the input, it'll return null
        array_walk($filters, fn(&$value) => $value = self::FILTERS[$value]); // gets the sanitation filter
    
        $filtered_data = filter_var_array($inputs, $filters);
    
        if ($trim) { return $this->trim_array($filtered_data); }
        else { return $filtered_data; }
    }
}