<?php

namespace DevHoro\Server\APIHandler;

use DevHoro\App\Horo;
use DevHoro\App\User;
use DevHoro\App\ActivityLog;
use DevHoro\Server;
use DevHoro\App\HoroLogic;


class FeedHoro extends Base
{
    const name = 'horo/feed';
    const description = 'Feed Horo';
    const params = [
        ''
    ];

    protected $result;

    function processRequest ($request) {
        $machineID = Server::getMachineID($request);
        $horoObj = Horo::first();
        $userObj = User::find($machineID);

        if($request->isGet()) {
            $this->result= $horoObj->hunger;
        }
        if($request->isPost()) {

            $feedVal = HoroLogic::Feed($userObj, $horoObj);

            // Feed will increase the love_degree
            $this->result = "";
            if($feedVal == -3001) {
                $this->result = "咱...生病了呐...吃不进去东西\r\n";
                return ;
            }
            for($i = 0; $i < $feedVal; $i++) {
                $this->result .= "\u{1f60b}\r\n";
            }

            if($this->result == "") {
                $this->result = "咱现在不想吃东西呐\r\n";
                return ;
            }

            $this->result .= "\r\n";
        }
    }

    /**
     * @return mixed
     */
    public function getResponse($response)
    {
        return $response->getBody()->write($this->result);
    }
}
