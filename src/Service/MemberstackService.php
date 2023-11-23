<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MemberstackService
{

    public function __construct(private readonly HttpClientInterface $client, private readonly string $memberstackApiKey)
    {
    }

    public function getUserDetails(string $userId): array
    {
        $response = $this->client->request('GET', 'https://admin.memberstack.com/members/'.$userId, [
            'headers' => [
                'X-API-KEY' => $this->memberstackApiKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }

        throw new \Exception('Failed to retrieve user details from Memberstack');
    }
}