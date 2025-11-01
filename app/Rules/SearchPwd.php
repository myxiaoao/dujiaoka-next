<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Rules;

use App\Models\BaseModel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SearchPwd implements ValidationRule
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
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (dujiaoka_config_get('is_open_search_pwd') == BaseModel::STATUS_OPEN && empty($value)) {
            $fail(__('dujiaoka.prompt.search_password_can_not_be_empty'));
        }
    }
}
