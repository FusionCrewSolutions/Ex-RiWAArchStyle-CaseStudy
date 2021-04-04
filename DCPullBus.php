<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

use \ExRiWAArch\User;
use \ExRiWAArch\Articles;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';

$c = new \Slim\Container();
$app = new \Slim\App($c);

$app->get('/', 'RYBIPHome');
function RYBIPHome($request, $response)
{
    ob_start();
    require_once 'index.php';
    $viewTemplate = ob_get_clean();

    return $response->getBody()->write($viewTemplate);
}

//-- USER =======================================================================
$app->post('/Login', function (Request $request, Response $response)
{
    if ($request->isXhr())
    {
        return $response->write((new User)->login($request->getBody()));
    }
});

$app->post('/chatroom/user', function (Request $request, Response $response)
{
    if ($request->isXhr())
    {
        return $response->write((new User)->notifyUserEnteringChatroom());
    }
});

$app->post('/chatroom/msg', function (Request $request, Response $response)
{
    if ($request->isXhr())
    {
        return $response->write((new User)->sendMsg($request->getBody()));
    }
});

//-- ARTICLES ======================================================================
$app->post('/article', function (Request $request, Response $response)
{
    if ($request->isXhr())
    {
        return $response->write((new Articles)->publishArticle($request->getBody()));
    }
});


//-- ERROR ==========================================================================
unset($app->getContainer()['notFoundHandler']);
$app->getContainer()['notFoundHandler'] = function ($c)
{
    return function ($request, $response) use ($c)
    {
        $response = new \Slim\Http\Response(404);

        //ob_start();
        //require_once 'RYBIP404.php';
        //$viewTemplate = ob_get_clean();

        return $response->write("Error");
    };
};

$app->run();
