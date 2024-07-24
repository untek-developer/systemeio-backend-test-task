<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalculatePriceControllerTest extends WebTestCase
{
    /**
     * @dataProvider validationErrorProvider
     * @param array $requestData
     * @param array $errors
     */
    public function testValidation(array $requestData, array $errors): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', $requestData);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(422);
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($errors, $responseData['errors']);
    }

    /**
     * @dataProvider successProvider
     * @param array $requestData
     * @param int|float $price
     */
    public function testSuccess(array $requestData, int|float $price): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', $requestData);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($price, $responseData['price']);
    }

    public function successProvider(): ?\Generator
    {
        yield [
            [
                "product" => 1,
                "taxNumber" => "FRGZ123456789",
                "couponCode" => "D15",
            ],
            100
        ];
        yield [
            [
                "product" => 1,
                "taxNumber" => "GR123456789",
                "couponCode" => "D15",
            ],
            100
        ];
        yield [
            [
                "product" => 1,
                "taxNumber" => "IT123456789",
                "couponCode" => "D15",
            ],
            100
        ];
        yield [
            [
                "product" => 1,
                "taxNumber" => "DE123456789",
                "couponCode" => "D15",
            ],
            100
        ];
    }

    public function validationErrorProvider(): ?\Generator
    {
        yield [
            [
                "product" => 1,
                "taxNumber" => "AS123456789",
                "couponCode" => "E15",
            ],
            [
                [
                    "field" => "taxNumber",
                    "message" => "This value is not valid.",
                ],
                [
                    "field" => "couponCode",
                    "message" => "This value is not valid.",
                ],
            ],
        ];
        yield [
            [
                "product" => 1,
                "taxNumber" => "FR123456789",
                "couponCode" => "D15",
            ],
            [
                [
                    "field" => "taxNumber",
                    "message" => "This value is not valid.",
                ],
            ],
        ];
    }
}
