<?php

namespace App\Http\Controllers;

use App\Services\NoteService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    protected NoteService $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->noteService->getAllNotes($request->client_id));
    }

    public function store(Request $request): JsonResponse
    {
        $note = $this->noteService->createNote($request->all());
        return response()->json($note, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->noteService->getNoteById($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $note = $this->noteService->updateNote($id, $request->all());
        return response()->json($note);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->noteService->deleteNote($id);
        return response()->json(['message' => 'Note deleted successfully']);
    }
}