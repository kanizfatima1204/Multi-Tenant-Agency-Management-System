<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $user = request()->user() ?? auth()->user();

            if ($user && !$user->isAdmin()) {
                $builder->where($builder->getModel()->getTable() . '.tenant_id', $user->tenant_id);
            }
        });

        static::creating(function (Model $model) {
            $user = request()->user() ?? auth()->user();

            if (!$model->tenant_id && $user && !$user->isAdmin()) {
                $model->tenant_id = $user->tenant_id;
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
