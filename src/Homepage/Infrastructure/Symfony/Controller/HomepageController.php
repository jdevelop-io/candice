<?php

declare(strict_types=1);

namespace Candice\Homepage\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('Homepage/index.html.twig');
    }
}
