<?php

namespace App\Http\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SaleServiceScope implements Scope
{
	public function apply(Builder $builder, Model $model)
	{
		@$builder->where($model->getTable() . '.' . 'type', "Serviço");
	}
}