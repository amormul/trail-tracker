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

    public function validate(array $data): array
    {
        $errors = [];

        $errors = array_merge($errors, $this->validateRequiredField($data, 'name', 'Name is required.'));
        $errors = array_merge($errors, $this->validateRequiredField($data, 'description', 'Description is required.'));
        $errors = array_merge($errors, $this->validateForeignKey($data, 'difficulty_id', 'difficulties', 'Valid difficulty is required.', 'Difficulty does not exist.'));
        $errors = array_merge($errors, $this->validateForeignKey($data, 'status_id', 'statuses', 'Valid status is required.', 'Status does not exist.'));
        $errors = array_merge($errors, $this->validateDateField($data, 'start_date', 'Start date is required.', 'Start date must be a valid date.', true));
        $errors = array_merge($errors, $this->validateDateField($data, 'end_date', 'End date is required.', 'End date must be a valid date.'));

        // Validate date relationships
        if (empty($errors['start_date']) && empty($errors['end_date']) && isset($data['start_date'], $data['end_date'])) {
            if (strtotime($data['end_date']) <= strtotime($data['start_date'])) {
                $errors['end_date'] = 'End date must be after start date.';
            }
        }

        return $errors;
    }

    private function validateRequiredField(array $data, string $field, string $errorMessage): array
    {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            return [$field => $errorMessage];
        }
        return [];
    }

    private function validateForeignKey(array $data, string $field, string $table, string $requiredErrorMessage, string $notExistErrorMessage): array
    {
        if (!isset($data[$field]) || !filter_var($data[$field], FILTER_VALIDATE_INT)) {
            return [$field => $requiredErrorMessage];
        } elseif (!$this->model->getById($table, 'id', (int)$data[$field])) {
            return [$field => $notExistErrorMessage];
        }
        return [];
    }

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

    private function isValidDate(string $date): bool
    {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $date);
        return $dateTime !== false && $dateTime->format('Y-m-d\TH:i') === $date;
    }
}
