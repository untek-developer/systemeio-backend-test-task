<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalculatePriceControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', [
            "product" => 1,
            "taxNumber" => "DE123456789",
            "couponCode" => "D15",
        ]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(100, $responseData['price']);
    }

    public function testValidation(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', [
            "product" => 1,
            "taxNumber" => "AS123456789",
            "couponCode" => "E15",
        ]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals([
            [
                "field" => "taxNumber",
                "message" => "This value is not valid.",
            ],
            [
                "field" => "couponCode",
                "message" => "This value is not valid.",
            ],
        ], $responseData['errors']);
    }

    public function testValidationTaxNumberForDe(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', [
            "product" => 1,
            "taxNumber" => "DE123456789",
            "couponCode" => "D15",
        ]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(100, $responseData['price']);
    }

    public function testValidationTaxNumberForIt(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', [
            "product" => 1,
            "taxNumber" => "IT123456789",
            "couponCode" => "D15",
        ]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(100, $responseData['price']);
    }

    public function testValidationTaxNumberForGr(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', [
            "product" => 1,
            "taxNumber" => "GR123456789",
            "couponCode" => "D15",
        ]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(100, $responseData['price']);
    }

    public function testValidationTaxNumberForFr(): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/calculate-price', [
            "product" => 1,
            "taxNumber" => "FRGZ123456789",
            "couponCode" => "D15",
        ]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(100, $responseData['price']);

        $client->jsonRequest('POST', '/calculate-price', [
            "product" => 1,
            "taxNumber" => "FR123456789",
            "couponCode" => "D15",
        ]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals([
            [
                "field" => "taxNumber",
                "message" => "This value is not valid.",
            ],
        ], $responseData['errors']);
    }
}
