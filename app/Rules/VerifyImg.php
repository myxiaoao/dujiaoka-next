<?php

namespace App\Rules;

use App\Models\BaseModel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyImg implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (dujiaoka_config_get('is_open_img_code') == BaseModel::STATUS_OPEN && !captcha_check($value)) {
            $fail(__('dujiaoka.prompt.image_verify_code_error'));
        }
    }
}
