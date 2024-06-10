<?php

namespace App\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\VarDumper\VarDumper;

class LoginControllerTest extends WebTestCase
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

        $formParams = [
            'json' => [ 
                'username' => 'admin@admin.com',
                'password' => '123456',
            ],
        ];

        $response = $this->client->request('POST', 'http://127.0.0.1:8000/api/login_check', $formParams);
        $this->assertEquals(200, $response->getStatusCode(), "La réponse de connexion n'est pas correcte.");

        $response = $this->client->request('GET', '/profile');
        $this->assertEquals(200, $response->getStatusCode(), "La page de profil n'est pas accessible.");
    }

    public function testBadLogin(): void
    {
        $response = $this->client->request('GET', '/connexion');
        $this->assertEquals(200, $response->getStatusCode(), "La page de connexion ne s'est pas chargée correctement.");

        $formParams = [
            'json' => [ 
                'username' => 'fake@mail.com',
                'password' => 'wrongpassword',
            ],
        ];

        try {
            $response = $this->client->request('POST', 'http://127.0.0.1:8000/api/login_check', $formParams);
            $this->fail("La réponse de connexion pour des informations incorrectes n'est pas correcte.");
        } catch (ClientException $e) {
            $this->assertEquals(401, $e->getResponse()->getStatusCode(), "La réponse de connexion pour des informations incorrectes n'est pas correcte.");
        }
    }

    // public function testMissingCredentials(): void
    // {
    //     $response = $this->client->request('POST', 'http://127.0.0.1:8000/api/login_check', [
    //         'json' => [
    //             'username' => '',
    //             'password' => '',
    //         ],
    //         'headers' => [
    //             'Content-Type' => 'application/ld+json',
    //         ],
    //     ]);

    //     $this->assertEquals(400, $response->getStatusCode(), "La réponse de connexion pour des informations manquantes n'est pas correcte.");

    //     $data = json_decode((string) $response->getBody(), true);
    //     $this->assertArrayHasKey('message', $data, "Le message d'erreur n'est pas présent dans la réponse.");
    //     $this->assertEquals('Missing credentials.', $data['message'], "Le message d'erreur n'est pas correct.");
    // }
}