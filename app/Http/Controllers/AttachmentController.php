<?php

namespace App\Http\Controllers;

use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttachmentController extends Controller
{
    protected AttachmentService $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->attachmentService->getAllAttachments($request->client_id));
    }

    public function store(Request $request): JsonResponse
    {
        // التحقق مما إذا كان هناك مصفوفة من الملفات
        if ($request->hasFile('files')) {
            $attachments = [];
            foreach ($request->file('files') as $file) {
                $attachments[] = $this->attachmentService->createAttachment($request->all(), $file);
            }
            return response()->json([
                'message' => 'Attachments created successfully',
                'data' => $attachments
            ], 201);
        }

        // في حال تم إرسال ملف واحد فقط بالطريقة القديمة
        $attachment = $this->attachmentService->createAttachment($request->all(), $request->file('file'));
        return response()->json($attachment, 201);
    }

    public function show(int $id)
    {
        return $this->attachmentService->viewAttachment($id);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $attachment = $this->attachmentService->updateAttachment($id, $request->all(), $request->file('file'));
        return response()->json([
            'message' => 'Attachment updated successfully',
            'data' => $attachment
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->attachmentService->deleteAttachment($id);
        return response()->json(['message' => 'Attachment deleted successfully']);
    }

    public function download(int $id)
    {
        return $this->attachmentService->downloadAttachment($id);
    }

    public function base64(int $id)
    {
        return $this->attachmentService->viewAttachmentBase64($id);
    }
}
