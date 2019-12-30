<?php
// src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DefaultController
{
  public function index(Environment $twig)
  {
    $content = $twig->render('base.html.twig');

    return new Response($content);
  }
}