<?php

namespace App\Http\Requests;

use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProviderRequest extends FormRequest
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

            'provider'     => Rule::in((new UserService())->getActiveUsersProviders()),
            'balanceMin'   => 'sometimes|numeric',
            'balanceMax'   => 'sometimes|numeric',
        ];
    }
}
