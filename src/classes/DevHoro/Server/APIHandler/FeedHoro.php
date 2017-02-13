<?php

namespace DevHoro\Server\APIHandler;

use DevHoro\App\Horo;
use DevHoro\App\User;
use DevHoro\App\ActivityLog;
use DevHoro\Server;


class FeedHoro extends Base
{
    const name = 'horo/feed';
    const description = 'Feed Horo';
    const params = [
        ''
    ];

    protected $result_array;

    function processRequest ($request) {
        $machineID = Server::getMachineID($request);
        $horoObj = Horo::first();
        $userObj = User::where('machine_id', $machineID)->first();

        if($request->isGet()) {
            $this->result_array = $horoObj->hunger;
        }
        if($request->isPost()) {
            $activeLog = new ActivityLog;
            // Feed the Wolf

            $feedVal =  $userObj->horo_love_degree/100;

            $activeLog->machine_id = $machineID;
            $activeLog->operation = 'Feed the wolf';
            $activeLog->params = $feedVal;
            $activeLog->save();
            $horoObj->hunger -= $feedVal;
            $horoObj->save();
            $this->result_array = "\u{1f60b}";
        }
    }

    /**
     * @return mixed
     */
    public function getResponse($response)
    {
        return $response->getBody()->write($this->result_array);
    }
}