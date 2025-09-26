<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\Captcha\Captcha as CapthaService;

class Captcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
		$captcha_service = new CapthaService;
		return $captcha_service->check($value, request('key'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '验证码不正确';
    }
}

