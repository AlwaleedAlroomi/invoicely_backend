<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;


trait BelongsToTeam
{
    /**
     * يتم استدعاء هذه الدالة تلقائياً عند تشغيل الموديل.
     */
    protected static function bootBelongsToTeam(): void
    {
        // إضافة الـ Scope لفلترة البيانات بناءً على الفريق المختار
        static::addGlobalScope('team_scope', function (Builder $builder) {
            if (auth()->check()) {
                // نفترض هنا أننا نخزن الـ team_id النشط في الـ session أو نجلب من التوكن
                $teamId = auth()->user()->current_team_id;

                if ($teamId) {
                    $builder->where('team_id', $teamId);
                }
            }
        });

        // تعيين team_id تلقائياً عند إنشاء سجل جديد
        static::creating(function ($model) {
            if (auth()->check() && ! $model->team_id) {
                $model->team_id = auth()->user()->current_team_id;
            }
        });
    }
}
