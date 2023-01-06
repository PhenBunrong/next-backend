<?php

namespace App\Validators;

use App\Validators\BaseValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class PostValidator.
 */
class PostValidator extends BaseValidator
{
    public function additionalRules(): array
    {
        $rules = [
            'title' => ['required'],
            'content' => ['required'],
        ];
        $this->rules[ValidatorInterface::RULE_CREATE] = $rules;
        $this->rules[ValidatorInterface::RULE_UPDATE] = $rules;

        return $this->rules;
    }
}
