<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OnboardingController extends AbstractController
{
    #[Route('/register')]
    public function register(): Response
    {
        return $this->render('onboarding/index.html.twig');
    }
}
