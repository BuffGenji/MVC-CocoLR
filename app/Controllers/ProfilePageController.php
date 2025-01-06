<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\AbstractClasses\Page;
use App\Services\Session;
use Slim\Psr7\Response as Psr7Response;
use Slim\Views\Twig;

class ProfilePageController extends Page
{

    public function __construct(private Twig $twig)
    {
        $this->twig = $twig;
    }


    public function render(Request $request, Response $response, $args): Response
    {
        $args['user'] = $_SESSION;
        return $this->twig->render($response, Page::PROFILE_PAGE_TEMPLATE, $args);
    }
}