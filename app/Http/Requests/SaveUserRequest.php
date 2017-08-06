<?php

namespace App\Http\Requests;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class SaveUserRequest extends FormRequest
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
            'name'                  => 'required|min:5',
            'username'              => 'required|unique:users,username,'.Auth::user()->id, 
            'password'              => 'required|same:password',
            'password_confirmation' => 'required|same:password',
        ];
    }
}
