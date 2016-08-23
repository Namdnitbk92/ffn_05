<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MatchRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'home_id' => 'required|exists:teams,id',
            'guest_id' => 'required|exists:teams,id',
            'league_season_id' => 'required|exists:league_season,id',
        ];
    }
}
