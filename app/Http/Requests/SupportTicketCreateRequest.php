<?php

namespace App\Http\Requests;

use App\Enum\SupportTicketDepartment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupportTicketCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'       => 'nullable|required_without:new_contact_email|exists:customers,id',
            'send_to_customer'  => 'sometimes|in:on',
            'title'             => 'required|string',
            'content'           => 'required|string',
            'department'        => 'required|enum_value:' . SupportTicketDepartment::class,
            'assigned_agent_id' => 'nullable|exists:users,id',
            'new_contact_email' => 'nullable|email',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required_without' => "Customer or Non-customer E-mail must be present."
        ];
    }
}
