<?php
namespace Core;

use PDO;
use Core\Database;
use Core\Filter\{Sanitation, Validation};

class Filter {
    function __construct(
        private Sanitation $sanitation,
        private Validation $validation
    ) {

    }

    public function filter(PDO $db, array $inputs, array $filters, array $messages=[]) : array {
        $sanitation_filters = [];
        $validation_filters = [];
    
        foreach ($filters as $field_name=>$value) {
            $sanitation_filters[$field_name] = array_splice($value, 0, 1)[0]; // this removes the first element from the array of strings because the first element is always the filter to sanitize
            $validation_filters[$field_name] = $value; // sets the rest of the array to be the validation filters
        }

        $sanitized_inputs = $this->sanitation->sanitize($inputs, $sanitation_filters);
        $errors = $this->validation->validate($db, $sanitized_inputs, $validation_filters, $messages);

        return [$sanitized_inputs, $errors];
    }

    public function filter_image(array $inputs, array $filters): array {
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