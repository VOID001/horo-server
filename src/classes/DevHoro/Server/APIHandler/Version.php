<?php

namespace DevHoro\Server\APIHandler;

class Version extends Base {

  const name = 'version';
  const description = 'Get current server version.';
  const params = [];

  function getResponse ($response) {
    return $response->withJson([
      'version' => \DevHoro\Server::version
    ]);
  }

}
