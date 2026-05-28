<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\User;
use App\Notifications\AttachmentCreatedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;

class AttachmentService
{
    /**
     * Get all attachments or filter by client.
     *
     * @param int|null $clientId
     * @return Collection
     */
    public function getAllAttachments(?int $clientId = null): Collection
    {
        Gate::authorize('view-attachments');

        if ($clientId) {
            return Attachment::where('client_id', $clientId)->get();
        }
        return Attachment::all();
    }

    /**
     * Get an attachment by ID.
     *
     * @param int $id
     * @return Attachment
     */
    public function getAttachmentById(int $id): Attachment
    {
        Gate::authorize('view-attachments');

        return Attachment::findOrFail($id);
    }

    /**
     * Create a new attachment.
     *
     * @param array $data
     * @param UploadedFile|null $file
     * @return Attachment
     * @throws ValidationException
     */
    public function createAttachment(array $data, ?UploadedFile $file = null): Attachment
    {
        Gate::authorize('create-attachments');

        Validator::make(array_merge($data, ['file' => $file]), [
            'client_id' => 'required|exists:clients,id',
            'file' => 'required|file|max:10240',
        ])->validate();

        $path = $file->store('attachments', 'public');

        $attachment = Attachment::create([
            'client_id' => $data['client_id'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
        ]);

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new AttachmentCreatedNotification($attachment));

        return $attachment;
    }

    /**
     * Update an attachment.
     *
     * @param int $id
     * @param array $data
     * @param UploadedFile|null $file
     * @return Attachment
     */
    public function updateAttachment(int $id, array $data, ?UploadedFile $file = null): Attachment
    {
        Gate::authorize('edit-attachments');

        $attachment = Attachment::findOrFail($id);

        Validator::make(array_merge($data, ['file' => $file]), [
            'client_id' => 'sometimes|exists:clients,id',
            'file' => 'nullable|file|max:10240',
        ])->validate();

        if (isset($data['client_id'])) {
            $attachment->client_id = $data['client_id'];
        }

        if ($file) {
            // Delete old file
            if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
            
            // Store new file
            $path = $file->store('attachments', 'public');
            $attachment->file_name = $file->getClientOriginalName();
            $attachment->file_path = $path;
            $attachment->file_type = $file->getMimeType();
        }

        $attachment->save();

        return $attachment;
    }

    /**
     * Delete an attachment.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAttachment(int $id): bool
    {
        Gate::authorize('delete-attachments');

        $attachment = Attachment::findOrFail($id);
        
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        return $attachment->delete();
    }

    /**
     * Get a response to view the attachment.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function viewAttachment(int $id)
    {
        Gate::authorize('view-attachments');

        $attachment = Attachment::findOrFail($id);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found on disk');
        }

        $mimeType = Storage::disk('public')->mimeType($attachment->file_path);

        return Storage::disk('public')->response($attachment->file_path, null, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $attachment->file_name . '"',
            'Access-Control-Allow-Origin' => '*',
            'X-Frame-Options'     => 'ALLOWALL',
        ]);
    }

    /**
     * إرجاع الملف كـ Base64 JSON (يتجاوز IDM تماماً)
     * IDM لا يعترض JSON responses
     */
    public function viewAttachmentBase64(int $id): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('view-attachments');

        $attachment = Attachment::findOrFail($id);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $content  = Storage::disk('public')->get($attachment->file_path);
        $mimeType = Storage::disk('public')->mimeType($attachment->file_path);

        return response()->json([
            'data'      => base64_encode($content),
            'mime_type' => $mimeType,
            'file_name' => $attachment->file_name,
        ])->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Get download response for an attachment.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function downloadAttachment(int $id)
    {
        Gate::authorize('view-attachments');

        $attachment = Attachment::findOrFail($id);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return response()->json(['message' => 'File not found on disk'], 404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}
