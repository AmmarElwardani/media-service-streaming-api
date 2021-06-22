<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValidation extends FormRequest
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
            'email' => 'email|required|unique:users,email,' . $this->id,
            'password' => 'nullable|min:4 ',
            'name' => 'required|max:20',
            'address' => 'nullable',
            'phone' => 'size:8|unique:users,phone,' . $this->id,
            'registerDate' =>'date',
            'accountType' => 'nullable|in:individual,company',
            'creditCardInfo' => 'nullable|unique:users,creditCardInfo,' . $this->id,
        ];
    }
}
