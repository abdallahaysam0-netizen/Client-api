<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 8px; }
        .header { text-align: center; color: #2d3748; margin-bottom: 20px; }
        .section { background: white; padding: 15px; margin-bottom: 15px; border-radius: 5px; border-left: 4px solid #4a5568; }
        .title { font-weight: bold; font-size: 1.2em; color: #2c5282; margin-bottom: 10px; }
        ul { list-style: none; padding: 0; }
        li { padding: 8px 0; border-bottom: 1px solid #edf2f7; }
        .footer { text-align: center; font-size: 0.8em; color: #718096; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ملخص النشاط اليومي</h1>
            <p>{{ now()->format('Y-m-d') }}</p>
        </div>

        <div class="section">
            <div class="title">الإحصائيات العامة</div>
            <p>عدد الملاحظات الجديدة: {{ $notesCount }}</p>
            <p>عدد الملفات المرفوعة: {{ $attachmentsCount }}</p>
        </div>

        @if($recentNotes->count() > 0)
        <div class="section">
            <div class="title">أحدث الملاحظات</div>
            <ul>
                @foreach($recentNotes as $note)
                    <li>
                        <strong>{{ $note->client->name ?? 'عميل غير معروف' }}:</strong> {{ $note->note }}
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($recentAttachments->count() > 0)
        <div class="section">
            <div class="title">أحدث الملفات</div>
            <ul>
                @foreach($recentAttachments as $attachment)
                    <li>
                        <strong>{{ $attachment->client->name ?? 'عميل غير معروف' }}:</strong> {{ $attachment->file_name }} ({{ $attachment->file_type }})
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="footer">
            <p>هذا إيميل تلقائي من نظام العملاء.</p>
        </div>
    </div>
</body>
</html>
