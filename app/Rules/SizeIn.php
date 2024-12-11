<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SizeIn implements Rule
{
    private $size = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $size)
    {
        $this->size = $size;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(in_array(count($value),$this->size)){
          return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute size must be in: '.implode(',',$this->size);
    }
}
