<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiAccessTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testAccessWithoutToken(): void
    {
        // Test d'accès à une route protégée sans token
        $this->client->request('GET', '/api/protected_route');
        
        // Vérifier que la réponse est un 401 Unauthorized
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testAccessWithToken(): void
    {
        // Récupérer un token valide pour un utilisateur de test
        $crawler = $this->client->request('POST', '/api/login_check', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'admin@admin.com',
            'password' => '123456'
        ]));

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $token = $response['token'];

        // Faire une requête à une route protégée avec le token
        $this->client->request('GET', '/api/protected_route', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);

        // Vérifier que la réponse est un 200 OK
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}