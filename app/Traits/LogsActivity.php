<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            static::logActivity($model, 'created');
        });

        static::updated(function (Model $model) {
            static::logActivity($model, 'updated');
        });

        static::deleted(function (Model $model) {
            static::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity(Model $model, string $action)
    {
        $description = static::getActivityDescription($model, $action);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'action' => $action,
            'description' => $description,
            'old_values' => $action === 'updated' ? $model->getOriginal() : null,
            'new_values' => $action !== 'deleted' ? $model->getAttributes() : null,
            'ip_address' => request()->ip(),
        ]);
    }

    protected static function getActivityDescription(Model $model, string $action): string
    {
        $modelName = class_basename($model);
        $name = $model->name ?? $model->title ?? $model->note ?? $model->file_name ?? "معرف #{$model->id}";
        
        // Limit note length in description
        if (isset($model->note) && strlen($name) > 30) {
            $name = mb_substr($name, 0, 30) . '...';
        }
        
        $translatedModels = [
            'Client' => 'موظف',
            'Note' => 'ملاحظة',
            'Attachment' => 'مرفق',
        ];

        $translatedActions = [
            'created' => 'إضافة',
            'updated' => 'تعديل',
            'deleted' => 'حذف',
        ];

        $m = $translatedModels[$modelName] ?? $modelName;
        $a = $translatedActions[$action] ?? $action;

        return "تم {$a} {$m}: {$name}";
    }
}
