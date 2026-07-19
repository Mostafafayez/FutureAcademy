<?php
namespace App\Services; 
use Illuminate\Support\Facades\Http;
 class DailyService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.daily.base_url');
        $this->apiKey  = config('services.daily.api_key');
    }

    protected function client()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ]);
    }

    public function createRoom(array $data = [])
    {
        return $this->client()
            ->post($this->baseUrl . '/rooms', $data)
            ->json();
    }

    public function getRooms()
    {
        return $this->client()
            ->get($this->baseUrl . '/rooms')
            ->json();
    }

    public function getRoom(string $name)
    {
        return $this->client()
            ->get($this->baseUrl . '/rooms/' . $name)
            ->json();
    }

    public function deleteRoom(string $name)
    {
        return $this->client()
            ->delete($this->baseUrl . '/rooms/' . $name)
            ->json();
    }

    public function createMeetingToken(array $data)
    {
        return $this->client()
            ->post($this->baseUrl . '/meeting-tokens', $data)
            ->json();
    }
}

