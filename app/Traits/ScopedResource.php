<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopedResource
{
    public static function bootScopedResource()
    {
        // 🔒 الكود التلقائي عند الإنشاء (تعبئة الحقول تلقائياً في الخلفية)
        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();

                // ربط العنصر بالشركة دائماً
                if (in_array('team_id', $model->getFillable()) && !$model->team_id) {
                    $model->team_id = $user->current_team_id;
                }

                // ربط العنصر بالفرع تلقائياً إذا كان المستخدم موظف فرع
                if (in_array('branch_id', $model->getFillable()) && $user->branch_id && !$model->branch_id) {
                    $model->branch_id = $user->branch_id;
                }
            }
        });

        // 👁️ قفل القراءة الذكي المشترك
        static::addGlobalScope('tenant_and_branch_filter', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();
                $table = $builder->getModel()->getTable();

                // 1. جدار الحماية الأول (الشركة): مطبق على الجميع دائماً
                if (in_array('team_id', $builder->getModel()->getFillable())) {
                    $builder->where($table . '.team_id', $user->current_team_id);
                }

                // 2. جدار الحماية الثاني (الفرع): يطبق فقط إذا كان المستخدم موظف فرع (وليس مديراً)
                if ($user->branch_id !== null) {
                    if ($table === 'branches') {
                        // إذا كنا نستعلم عن جدول الفروع نفسه، نجعله يرى فرعه فقط
                        $builder->where($table . '.id', $user->branch_id);
                    } elseif (in_array('branch_id', $builder->getModel()->getFillable())) {
                        // إذا كنا نستعلم عن الفواتير أو المدفوعات، نفلتر بفرعه
                        $builder->where($table . '.branch_id', $user->branch_id);
                    }
                }
            }
        });
    }
}
