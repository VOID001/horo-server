<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Routes

$app->get('/', function (Request $request, Response $response) {
  return $response->withRedirect("https://wiki.yoitsu.moe/wiki/Portal:Dev.horo");
});


$app->group('/api', function () {

  $this->get('[/]', function (Request $request, Response $response) {
    return $response->withRedirect('/api/v0');
  });

  $this->group('/v0', function () {

    $this->get('[/]', function (Request $request, Response $response) {
      return $this->renderer->render($response, 'api-index.phtml', [
        'apis' => DevHoro\Server\APIHandler::getAllApis()
      ]);
    });

    foreach (DevHoro\Server\APIHandler::getAllApis() as $name => $info) {
      $this->map($info['methods'], '/'.$name, function (Request $request, Response $response, $args) use ($name, $info) {
        $class_name = 'DevHoro\Server\APIHandler\\'.$info['class_name'];
        $handler = new $class_name(array_replace($args, $request->getQueryParams()));
        $handler->processRequest($request);
        return $handler->getResponse($response);
      });
    }

  });

});
