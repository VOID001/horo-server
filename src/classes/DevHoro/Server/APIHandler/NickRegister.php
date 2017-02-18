<?php


namespace DevHoro\Server\APIHandler;

use DevHoro\App\User;
use DevHoro\Server;


class NickRegister
{
    const name = 'Nick';
    const description = 'Register MachineID to a Nickname';
    const params = [];

    public $options;
    protected $result;

    function __construct ($options) {
        $this->options = $options;
    }

    function processRequest ($request) {
        $machineID = Server::getMachineID($request);
        $userObj = User::find($machineID);
        if ($userObj->user_name != "") {
            $this->result = "咱已经知道你的名字了, 轻易改名会让咱认为你是个随意的人呐";
            return;
        }

        $userObj->user_name = $request->getParsedBody()["nick"];
        if ($userObj->user_name == "") {
            $this->result = "Error: nickname cannot be null";
            return;
        }
        if (strlen($userObj->user_name) > 12) {
            $this->result = "Error: nickname cannot longer than 12";
            return;
        }
        $userObj->save();

        $this->result = $userObj->user_name." ,咱不会忘记你的名字了";
        return;
    }

    function getResponse ($response) {
        return $response->getBody()->write($this->result);
    }

}
