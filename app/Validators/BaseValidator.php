<?php

namespace App\Validators;

use Prettus\Validator\LaravelValidator;

abstract class BaseValidator extends LaravelValidator
{
    /**
     * Get rule for validation by action ValidatorInterface::RULE_CREATE or ValidatorInterface::RULE_UPDATE.
     *
     * Default rule: ValidatorInterface::RULE_CREATE
     *
     * @param  null  $action
     * @return array
     */
    public function getRules($action = null): array
    {
        $rules = $this->rules;
        $additionalRules = $this->additionalRules();
        $rules = $this->array_merge_recursive_distinct($rules, $additionalRules);
        if (isset($rules[$action])) {
            $rules = $rules[$action];
        }

        return $this->parserValidationRules($rules, $this->id);
    }

    /**
     * @return array
     */
    public function additionalRules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function additionalAttributes(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return array_merge_recursive($this->attributes, $this->additionalAttributes());
    }

    /**
     * @return array
     */
    public function additionalMessages(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return array_merge_recursive($this->messages, $this->additionalMessages());
    }

    /**
     * @param  array  $array1
     * @param  array  $array2
     * @return array
     */
    private function array_merge_recursive_distinct(array &$array1, array &$array2): array
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
