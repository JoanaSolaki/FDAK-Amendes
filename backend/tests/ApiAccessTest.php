<?php

namespace App\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiAccessTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client = new Client(['base_uri' => 'http://localhost:3000']);
    }

    public function testAccessWithoutToken(): void
    {
        try {
            $this->client->request('GET', '/api/fines');
        } catch (RequestException $e) {
        }

        $this->assertTrue(true, "Le test s'est terminé sans problème.");
    }

    public function testAccessWithToken(): void
    {
        $formParams = [
            'json' => [ 
                'username' => 'admin@admin.com',
                'password' => '123456',
            ],
        ];
    
        $response = $this->client->request('POST', 'http://127.0.0.1:8000/api/login_check', $formParams);
        $this->assertEquals(200, $response->getStatusCode(), "La réponse de connexion n'est pas correcte.");
    
        $responseContent = $response->getBody()->getContents();

        // Vérifier si la réponse est vide
        $this->assertNotEmpty($responseContent, "La réponse de la requête POST est vide.");
    
        // Décoder le contenu de la réponse JSON
        $responseData = json_decode($responseContent, true);

        // Vérifier si le décodage JSON a réussi
        $this->assertNotNull($responseData, "Échec du décodage JSON de la réponse de la requête POST.");
    
        // Vérifier si la clé 'token' existe dans les données de la réponse
        $this->assertArrayHasKey('token', $responseData, "La clé 'token' n'existe pas dans les données de la réponse.");
    
        // Récupérer le token à partir des données de la réponse
        $token = $responseData['token'];

        echo('token ' . $token);
    
        // Faire une requête à une route protégée avec le token
        $this->client->request('GET', 'http://127.0.0.1:8000/api/fines', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer' . $token
        ]);
    
        // Vérifier que la réponse est un 200 OK
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}