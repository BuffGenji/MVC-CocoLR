<?php

declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\HomeModel;
use Slim\Views\Twig;

/**
 * Class HomeController
 * @package App\Controllers
 * @description This class is responsible for rendering the home page
 */

class HomeController extends Page
{
    private Twig $twig;
    private $model;


    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
        $this->model = new HomeModel();
    }

    #[Page('/', name: 'home_render', methods: ['get'])]
    public function render(Request $request, Response $response, $args): Response
    {
        return $this->twig->render(
            $response,
            HomeController::LANDING_PAGE_TEMPLATE,
            $args
        );
    }
}
