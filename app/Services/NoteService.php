<?php

namespace App\Services;

use App\Models\Note;
use App\Models\User;
use App\Notifications\NoteCreatedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NoteService
{
    /**
     * Get all notes or filter by client.
     *
     * @param int|null $clientId
     * @return Collection
     */
    public function getAllNotes(?int $clientId = null): Collection
    {
        if ($clientId) {
            return Note::with('client')->where('client_id', $clientId)->get();
        }
        return Note::with('client')->get();
    }

    /**
     * Get a note by ID.
     *
     * @param int $id
     * @return Note
     */
    public function getNoteById(int $id): Note
    {
        return Note::with('client')->findOrFail($id);
    }

    /**
     * Create a new note.
     *
     * @param array $data
     * @return Note
     * @throws ValidationException
     */
    public function createNote(array $data): Note
    {
        Validator::make($data, [
            'client_id' => 'required|exists:clients,id',
            'note' => 'required|string',
        ])->validate();

        $note = Note::create($data);

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NoteCreatedNotification($note));

        return $note;
    }

    /**
     * Update an existing note.
     *
     * @param int $id
     * @param array $data
     * @return Note
     * @throws ValidationException
     */
    public function updateNote(int $id, array $data): Note
    {
        Validator::make($data, [
            'note' => 'required|string',
        ])->validate();

        $note = Note::findOrFail($id);
        $note->update($data);
        return $note;
    }

    /**
     * Delete a note.
     *
     * @param int $id
     * @return bool
     */
    public function deleteNote(int $id): bool
    {
        $note = Note::findOrFail($id);
        return $note->delete();
    }
}
