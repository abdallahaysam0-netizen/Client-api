<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ClientService
{
    /**
     * Get all clients.
     *
     * @return Collection
     */
    public function getAllClients(): Collection
    {
        return Client::all();
    }

    /**
     * Get a client by ID.
     *
     * @param int $id
     * @return Client
     */
    public function getClientById(int $id): Client
    {
        return Client::findOrFail($id);
    }

    /**
     * Create a new client.
     *
     * @param array $data
     * @return Client
     * @throws ValidationException
     */
    public function createClient(array $data): Client
    {
        Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:11',
            'status' => 'nullable|in:active,inactive,pending',
        ])->validate();

        return Client::create($data);
    }

    /**
     * Update an existing client.
     *
     * @param int $id
     * @param array $data
     * @return Client
     * @throws ValidationException
     */
    public function updateClient(int $id, array $data): Client
    {
        Validator::make($data, [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,' . $id,
            'phone' => 'nullable|string|max:11',
            'status' => 'nullable|in:active,inactive,pending',
        ])->validate();

        $client = Client::findOrFail($id);
        $client->update($data);
        return $client;
    }

    /**
     * Delete a client.
     *
     * @param int $id
     * @return bool
     */
    public function deleteClient(int $id): bool
    {
        $client = Client::findOrFail($id);
        return $client->delete();
    }
}
