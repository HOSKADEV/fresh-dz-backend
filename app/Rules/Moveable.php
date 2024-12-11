<?php

namespace App\Rules;

use App\Models\Section;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class Moveable implements Rule
{
  private $section = null;
  private $rank = null;
    /**
     * Create a new rule instance.
     * @param Request $request
     * @return void
     */
    public function __construct($request)
    {
        $this->section = Section::findOrFail($request->section_id);
        $this->rank = $request->rank;
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

        $rank_min = min([$this->section->rank, $this->rank]);
        $rank_max = max([$this->section->rank, $this->rank]);

        $sections = Section::whereBetween('rank', [$rank_min,$rank_max])
        ->where('moveable',0)->get()->toArray();

        if(count($sections) == 0){
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
        return 'The selected :attribute is invalid.';
    }
}
