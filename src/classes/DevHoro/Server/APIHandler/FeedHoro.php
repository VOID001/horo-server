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

    protected $result;

    function processRequest ($request) {
        $machineID = Server::getMachineID($request);
        $horoObj = Horo::first();
        $userObj = User::find($machineID);

        if($request->isGet()) {
            $this->result= $horoObj->hunger;
        }
        if($request->isPost()) {
            $activeLog = new ActivityLog;
            // Feed the Wolf

            // Calculate the feedValue
            $getfeedVal = function($userObj) {
                $moreHungry = false;
                // Probability of eat 3
                $rate =  (0.4 / (1 + exp(-3 * $userObj->affection / 100)) - 0.2);
                //echo "Rate3: $rate\r\n";
                if ($rate < 0) {
                    $moreHungry = true;
                };
                $toss = rand(1, 1000) * 1.0 / 1000;
                if ($toss < $rate)
                    return $moreHungry ? -3: 3;

                $moreHungry = false;
                // Probability of eat 2
                $rate =  (0.6 / (1 + exp(-3 * $userObj->affection / 100)) - 0.3);
                //echo "Rate2: $rate\r\n";
                if ($rate < 0) {
                    $moreHungry = true;
                };
                if ($toss < $rate)
                    return $moreHungry ? -2: 2;

                $moreHungry = false;
                $rate =  (1 / (1 + exp(-3 * $userObj->affection / 100)) - 0.5);
                //echo "Rate1: $rate\r\n";
                if ($rate < 0) {
                    $moreHungry = true;
                };
                if ($toss < $rate)
                    return $moreHungry ? -1: 1;
                return 0;
            };

            $feedVal = $getfeedVal($userObj);
            //echo "feedVal: $feedVal";

            // If feed too frequently will lose health

            if ($feedVal != 0) {
                $activeLog->machine_id = $machineID;
                $activeLog->operation = 'Feed the wolf';
                $activeLog->params = $feedVal;
                $activeLog->save();
            }

            $horoObj->hunger -= $feedVal;
            $horoObj->save();

            // Feed will increase the love_degree
            $userObj->food_contrib += $feedVal;
            $userObj->affection += $feedVal / 2.0;
            $userObj->update();

            $this->result = "";
            for($i = 0; $i < $feedVal; $i++) {
                $this->result .= "\u{1f60b}";
            }
            if($this->result == "")
                $this->result = "咱现在不想吃东西呐";
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
