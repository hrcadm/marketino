<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enum\UserRole;

class UserRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'name'          => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'role'  =>  'required|enum_value:' . UserRole::class,
                    'groups'  =>  'nullable|array',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'          => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users,email,'.$this->user->id,
                    'role'  =>  'required|enum_value:' . UserRole::class,
                    'groups'  =>  'nullable|array',
                ];
            }
            default:break;
        }
    }
}
