<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DevHoro\App\User;
use DevHoro\Server;
use DevHoro\App\Horo;

// Middleware

$mw = function($request, $response, $next) {
    $id = Server::getMachineID($request);
    if ($id == "") {
        $resp = array("code" => 400, "message" => "invalid request");
        return $response->withStatus(400)->withJson($resp);
    }

    // Check if the id is registered
    $user = User::where('machine_id', $id)->first();
    if ($user == NULL) {
        $user = new User;
        $user->machine_id = $id;
        $user->affection = 50;
        $user->pending_time = 1;
        $user->save();
    }

    $user = User::find($id);

    // Check for pending time, to avoid flooding request
    //echo "Current TS: ".time();
    $interval = time() - $user->last_request_time;
    //echo "Interval : $interval";

    if ($interval < $user->pending_time) {
        // Request too frequently
        $user->pending_time = $user->pending_time * 2;
        $user->last_request_time = time();
        $user->affection -= 5;
        $user->update();
        $resp = array("code" => 429, "message" => "request too frequent");
        return $response->withStatus(429)->withJson($resp);
    }
    $user->pending_time = 1;
    $user->last_request_time = time();
    $user->update();

    $response= $next($request, $response);
    return $response;
};

$horoCare = function($request, $response, $next) {
    $id = Server::getMachineID($request);
    $user = User::find($id);

    $user->affection = $user->affection + (rand(3, 10) * 1.0 / 100);
    $user->update();
    $response= $next($request, $response);
    return $response;
};

// Routes

$app->get('/', function (Request $request, Response $response) {
    $horo = Horo::all()[0];
    $users = User::TopNFavoriteUser(10);
    return $this->renderer->render($response, "index.phtml",[
        "stats" => $horo->Status(),
        "users" => $users,
    ]);
});


$app->group('/api', function () {

    $this->group('/v0', function () {

        foreach (DevHoro\Server\APIHandler::getAllApis() as $name => $info) {
            $this->map($info['methods'], '/'.$name, function (Request $request, Response $response, $args) use ($name, $info) {
                $class_name = 'DevHoro\Server\APIHandler\\'.$info['class_name'];
                $handler = new $class_name(array_replace($args, $request->getQueryParams()));
                $handler->processRequest($request);
                return $handler->getResponse($response);
            });
        }

    });

})/*add($horoCare)*/->add($mw);
