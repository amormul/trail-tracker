<?php

namespace app\core;

use app\models\Trip;
use DateTime;

class TripValidator
{
    protected Trip $model;

    public function __construct()
    {
        $this->model = new Trip();
    }

    /**
     * Validates the provided data for a trip.
     *
     * @param array $data The data to validate.
     * @return array An array of validation errors.
     */
    public function validate(array $data): array
    {
        $errors = [];

        $errors = array_merge($errors, $this->validateRequiredField($data, 'name', 'Name is required.'));
        $errors = array_merge($errors, $this->validateRequiredField($data, 'description', 'Description is required.'));
        $errors = array_merge($errors, $this->validateForeignKey($data, 'difficulty_id', 'difficulties', 'Valid difficulty is required.', 'Difficulty does not exist.'));
        $errors = array_merge($errors, $this->validateForeignKey($data, 'status_id', 'statuses', 'Valid status is required.', 'Status does not exist.'));
        $errors = array_merge($errors, $this->validateDateField($data, 'start_date', 'Start date is required.', 'Start date must be a valid date.', true));
        $errors = array_merge($errors, $this->validateDateField($data, 'end_date', 'End date is required.', 'End date must be a valid date.'));

        if (empty($errors['start_date']) && empty($errors['end_date']) && isset($data['start_date'], $data['end_date'])) {
            if (strtotime($data['end_date']) <= strtotime($data['start_date'])) {
                $errors['end_date'] = 'End date must be after start date.';
            }
        }

        return $errors;
    }

    /**
     * Validates that a required field is present and not empty.
     *
     * @param array $data The data to validate.
     * @param string $field The field to check.
     * @param string $errorMessage The error message if validation fails.
     * @return array An array containing the validation error, if any.
     */
    private function validateRequiredField(array $data, string $field, string $errorMessage): array
    {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            return [$field => $errorMessage];
        }
        return [];
    }

    /**
     * Validates a foreign key field.
     *
     * @param array $data The data to validate.
     * @param string $field The field to check.
     * @param string $table The table to verify the foreign key against.
     * @param string $requiredErrorMessage The error message if the field is missing.
     * @param string $notExistErrorMessage The error message if the foreign key does not exist.
     * @return array An array containing the validation error, if any.
     */
    private function validateForeignKey(array $data, string $field, string $table, string $requiredErrorMessage, string $notExistErrorMessage): array
    {
        if (!isset($data[$field]) || !filter_var($data[$field], FILTER_VALIDATE_INT)) {
            return [$field => $requiredErrorMessage];
        } elseif (!$this->model->getById($table, 'id', (int)$data[$field])) {
            return [$field => $notExistErrorMessage];
        }
        return [];
    }

    /**
     * Validates a date field.
     *
     * @param array $data The data to validate.
     * @param string $field The field to check.
     * @param string $requiredErrorMessage The error message if the field is missing.
     * @param string $invalidErrorMessage The error message if the date is invalid.
     * @param bool $checkPast Whether to ensure the date is not in the past.
     * @return array An array containing the validation error, if any.
     */
    private function validateDateField(array $data, string $field, string $requiredErrorMessage, string $invalidErrorMessage, bool $checkPast = false): array
    {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            return [$field => $requiredErrorMessage];
        } elseif (!$this->isValidDate($data[$field])) {
            return [$field => $invalidErrorMessage];
        } elseif ($checkPast && strtotime($data[$field]) < time()) {
            return [$field => ucfirst($field) . ' cannot be in the past.'];
        }
        return [];
    }

    /**
     * Checks if a date is in a valid format.
     *
     * @param string $date The date string to validate.
     * @return bool True if the date is valid, false otherwise.
     */
    private function isValidDate(string $date): bool
    {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $date);
        return $dateTime !== false && $dateTime->format('Y-m-d\TH:i') === $date;
    }

    /**
     * Validates the photo upload field.
     *
     * @param array &$errors Reference to the errors array to update.
     * @return void
     */
    public function validatePhotoUpload(array &$errors): void
    {
        if (!empty($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = 'Photo is required and must be a valid file.';
        }
    }
}
