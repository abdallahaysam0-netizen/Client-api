<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    protected ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->clientService->getAllClients());
    }

    public function store(Request $request): JsonResponse
    {
        $client = $this->clientService->createClient($request->all());
        return response()->json($client, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->clientService->getClientById($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $client = $this->clientService->updateClient($id, $request->all());
        return response()->json($client);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->clientService->deleteClient($id);
        return response()->json(['message' => 'Client deleted successfully']);
    }
}
