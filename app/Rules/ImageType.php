<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class ImageType implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_uploaded_file($value)) {
            return false; // File was not uploaded
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png'];
        $fileMimeType = $value->getMimeType();
        return in_array($fileMimeType, $allowedMimeTypes);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid image (JPEG, PNG).';
    }
}
