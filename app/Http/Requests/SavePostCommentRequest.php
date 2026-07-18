<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePostCommentRequest extends FormRequest
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
            'message' => ['required', 'string', 'min:1', 'max:1000'],
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'reply_to_id' => ['nullable', 'integer', 'exists:post_comments,id'],
        ];
    }
}
