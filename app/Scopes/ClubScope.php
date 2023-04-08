<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ClubScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check() && auth()->user()->club_id) {
            $builder->where(function ($query) {
                $query->where('home_team_id', auth()->user()->club_id)
                    ->orWhere('away_team_id', auth()->user()->club_id);
            });
        }
    }
}
