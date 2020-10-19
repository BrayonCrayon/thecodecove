<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'post_id' => 'sometimes|required|exists:posts,id',
            'text' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'parent_id' => 'sometimes|required|exists:comments,id'
        ];
    }

    public function messages()
    {
        return [
            'post_id.exists' => 'Post does not exist.',
            'text.required' => 'Comment text is required',
            'text.string' => 'Comment text cannot be empty',
            'user_id.required' => 'User is required to submit comment',
        ];
    }


}
