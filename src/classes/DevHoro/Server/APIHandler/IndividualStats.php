<?php

namespace DevHoro\Server\APIHandler;

use DevHoro\App\User;
use DevHoro\Server;

class IndividualStats extends Base {

  const name = 'individual/stats';
  const description = 'Get stats of a user.';
  const params = [
    'user_id' => 'Unique ID of the user.'
  ];

  protected $user_id;
  protected $result;

  function processRequest ($request) {
    $machineID = Server::getMachineID($request);
    $user = User::where('machine_id', $machineID)->first();
    $name = $user->name;
    $love = $user->horo_love_degree;
    $this->result  = "机器ID: $machineID\r\n主(shi)人(wu)名称:$name\r\n亲密度: $love\r\n";
  }

  function getResponse ($response) {
    return $response->getBody()->write($this->result);
  }

}
