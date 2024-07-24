<?php

namespace App\Controller;

use App\Dto\CalculatePriceDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CalculatePriceController extends AbstractController
{
    #[Route('/calculate-price', name: 'app_calculate_price')]
    public function index(#[MapRequestPayload] CalculatePriceDto $calculatePriceRequest): JsonResponse
    {
        return new JsonResponse([
            'price' => 100,
        ]);
    }
}
