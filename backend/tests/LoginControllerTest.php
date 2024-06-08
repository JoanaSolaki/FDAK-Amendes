<?php

namespace App\Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client(['base_uri' => 'http://localhost:3000']);
    }

    public function testGoodLogin(): void
    {
        $response = $this->client->request('GET', '/connexion');

        $this->assertEquals(200, $response->getStatusCode(), "La page de connexion ne s'est pas chargée correctement.");

        $html = (string) $response->getBody();

        $this->assertStringContainsString('<form', $html, "Le formulaire de connexion n'est pas présent sur la page.");

        $response = $this->client->request('POST', 'http://127.0.0.1:8000/api/login_check/api/login_check', [
            'json' => [
                'username' => 'admin@admin.com',
                'password' => '123456',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode(), "La réponse de connexion n'est pas correcte.");

        $data = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('token', $data, "Le token n'est pas présent dans la réponse.");

        $token = $data['token'];

        $response = $this->client->request('GET', '/profile', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode(), "La page de profil n'est pas accessible.");
    }

    public function testBadLogin(): void
    {
        $response = $this->client->request('POST', 'http://127.0.0.1:8000/api/login_check/api/login_check', [
            'json' => [
                'username' => 'fake@user.com',
                'password' => 'wrongpassword',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode(), "La réponse de connexion pour des informations incorrectes n'est pas correcte.");

        $data = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('message', $data, "Le message d'erreur n'est pas présent dans la réponse.");
        $this->assertEquals('Invalid credentials.', $data['message'], "Le message d'erreur n'est pas correct.");
    }

    public function testMissingCredentials(): void
    {
        $response = $this->client->request('POST', 'http://127.0.0.1:8000/api/login_check', [
            'json' => [
                'username' => '',
                'password' => '',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertEquals(400, $response->getStatusCode(), "La réponse de connexion pour des informations manquantes n'est pas correcte.");

        $data = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('message', $data, "Le message d'erreur n'est pas présent dans la réponse.");
        $this->assertEquals('Missing credentials.', $data['message'], "Le message d'erreur n'est pas correct.");
    }
}