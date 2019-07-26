<?php

namespace App\Http\Requests\Script;

use App\Models\Script;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RemoveUserFromTeamRequest extends FormRequest
{
    /** @var Script $script */
    private $script;

    public function authorize()
    {
        $this->script = $this->route('script');

        return Auth::check() && Auth::user()->owns($this->script);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required', 'email',
                Rule::exists((new User)->getTable(), 'email')
                    ->whereNot('id', $this->script->user_id),
            ]
        ];
    }
}
