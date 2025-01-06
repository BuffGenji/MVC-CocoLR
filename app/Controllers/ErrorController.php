<?php
declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use App\Services\Session;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class ErrorController extends Page
{

    public function __construct(private Twig $twig)
    {
        $this->twig = $twig;
    }

    public function render(Request $request, Response $response, $args) : Response
    {
        return $this->twig->render($response, Page::ERROR_PAGE_NOT_FOUND, $args);
    }
}