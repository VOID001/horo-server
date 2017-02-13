<?php

namespace DevHoro\Server\APIHandler;

use DevHoro\App\User;
use DevHoro\Server;

class IndividualStats extends Base {

    const name = 'individual/stats';
    const description = 'Get stats of a user.';

    protected $user_id;
    protected $result;

    function processRequest ($request) {
        $machineID = Server::getMachineID($request);
        $user = User::where('machine_id', $machineID)->first();
        $name = $user->name;
        $love = $user->horo_love_degree;
        $food = $user->food_contrib;
        $clean = $user->clean_contrib;
        $knowlege = $user->knowlege_contrib;
        $this->result  =
        "机器ID: $machineID\r\n".
        "主(shi)人(wu)名称:$name\r\n".
        "亲密度: $love\r\n".
        "投食总量: $food kg\r\n".
        "清洁度贡献: $clean\r\n".
        "知识贡献: $knowlege\r\n";
    }

    function getResponse ($response) {
        return $response->getBody()->write($this->result);
    }

}
