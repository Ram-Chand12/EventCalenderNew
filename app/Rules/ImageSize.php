<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class ImageSize implements Rule
{
    /**
     * The maximum allowed size for the image in kilobytes.
     *
     * @var int
     */
    protected $maxSize;

    /**
     * Create a new rule instance.
     *
     * @param  int  $maxSize
     * @return void
     */
    public function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
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
        if (!$value instanceof UploadedFile) {
            return false; // Not an uploaded file
        }

        return $value->getSize() <= $this->maxSize * 1024; // Convert KB to bytes
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
  
        return "The :attribute must be less than 2000 kb";
    }
  
}
