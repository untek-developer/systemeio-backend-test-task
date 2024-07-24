<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/purchase', [
            "product" => 1,
            "taxNumber" => "IT12345678900",
            "couponCode" => "D15",
            "paymentProcessor" => "paypal",
        ]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Welcome to your new controller!', $responseData['message']);
        $this->assertEquals('src/Controller/PurchaseController.php', $responseData['path']);
    }

    public function testValidation(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/purchase', [
            "product" => 1,
            "taxNumber" => "AS123456789",
            "couponCode" => "E15",
            "paymentProcessor" => "webmoney",
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
            [
                "field" => "paymentProcessor",
                "message" => "This value is not valid.",
            ],
        ], $responseData['errors']);
    }
}
