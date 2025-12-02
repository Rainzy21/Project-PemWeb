<?php

namespace Core\Traits;

trait ValidatesInput
{
    protected array $validationErrors = [];

    /**
     * Validate input
     */
    protected function validate(array $data, array $rules): array
    {
        $this->validationErrors = [];

        foreach ($rules as $field => $fieldRules) {
            $rulesArray = explode('|', $fieldRules);
            $value = $data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return $this->validationErrors;
    }

    /**
     * Apply validation rule
     */
    private function applyRule(string $field, mixed $value, string $rule): void
    {
        $fieldLabel = ucfirst(str_replace('_', ' ', $field));

        // required
        if ($rule === 'required' && empty($value)) {
            $this->validationErrors[$field] = "{$fieldLabel} is required";
            return;
        }

        // email
        if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[$field] = "{$fieldLabel} must be a valid email";
            return;
        }

        // numeric
        if ($rule === 'numeric' && !empty($value) && !is_numeric($value)) {
            $this->validationErrors[$field] = "{$fieldLabel} must be a number";
            return;
        }

        // min:length
        if (strpos($rule, 'min:') === 0) {
            $min = (int) substr($rule, 4);
            if (strlen($value) < $min) {
                $this->validationErrors[$field] = "{$fieldLabel} must be at least {$min} characters";
            }
            return;
        }

        // max:length
        if (strpos($rule, 'max:') === 0) {
            $max = (int) substr($rule, 4);
            if (strlen($value) > $max) {
                $this->validationErrors[$field] = "{$fieldLabel} must not exceed {$max} characters";
            }
            return;
        }

        // confirmed (password confirmation)
        if ($rule === 'confirmed') {
            $confirmField = $field . '_confirmation';
            $confirmValue = $_POST[$confirmField] ?? null;
            if ($value !== $confirmValue) {
                $this->validationErrors[$field] = "{$fieldLabel} confirmation does not match";
            }
        }
    }

    /**
     * Check if validation passed
     */
    protected function validationPassed(): bool
    {
        return empty($this->validationErrors);
    }

    /**
     * Get validation errors
     */
    protected function getErrors(): array
    {
        return $this->validationErrors;
    }

    /**
     * Get first error
     */
    protected function getFirstError(): ?string
    {
        return !empty($this->validationErrors) 
            ? reset($this->validationErrors) 
            : null;
    }
}