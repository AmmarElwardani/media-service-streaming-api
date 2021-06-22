<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:mp4,3gp,mov,flv,avi,wmv,ts|max:100040',
            'asset_id' => 'required'
        ];
    }
}
