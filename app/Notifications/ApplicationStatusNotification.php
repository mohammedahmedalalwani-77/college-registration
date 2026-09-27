<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $status,
        public string $majorName,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'approved' => '🎉 تم قبول طلبك بنجاح!',
            'rejected' => '❌ تم رفض طلب التسجيل',
            'action_required' => '⚠️ يتطلب إجراء / تعديل بيانات الطلب',
            'pending' => '⏳ طلبك قيد المراجعة',
        ];

        return [
            'title' => $statusLabels[$this->status] ?? 'تحديث حالة الطلب',
            'message' => "تم تحديث حالة طلبك لتخصص ({$this->majorName})" . ($this->reason ? ": {$this->reason}" : ''),
            'status' => $this->status,
        ];
    }
}
