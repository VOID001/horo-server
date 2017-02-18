<?php

namespace DevHoro\App;
use DevHoro\App\User as User;
use DevHoro\App\Horo as Horo;

define('MAX_HUNGER', 500);
define('MIN_HUNGER', -500);

class HoroLogic
{
    /**
     * Action Performed when horo is feeded
     *
     * @param User $user the user Object
     * @param Horo $horo the horo Object
     * @return $feedVal int
     */
    public static function Feed(User $user, Horo $horo) {
        $activeLog = new ActivityLog;

        // Now horo won't eat anything
        if ($horo->hp < 20) {
            $feedVal = -3001;
            return $feedVal;
        }

        // If you feed horo too much, she will dislike you somehow
        if ((MAX_HUNGER - $horo->hunger) * 0.3 < $user->food_contrib) {
            $feedVal = 0;
            $user->affection -= rand(1, 3000) * 1.0 / 1000;
            $user->save();
            return $feedVal;
        }

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

        $feedVal = $getfeedVal($user);

        if ($feedVal != 0) {
            $activeLog->machine_id = $user->machine_id;
            $activeLog->operation = 'Feed the wolf';
            $activeLog->params = $feedVal;
            $activeLog->save();
        }

        // Judge If Horo is sick
        if ($horo->hunger < MIN_HUNGER) {
            $hp_decrease = $horo->hp * (rand(1, 100) * 1.0 / 5000);
            $horo->hp = $horo->hp - $hp_decrease;
            $user->clean_contrib = $user->clean_contrib - $hp_decrease;
        }

        $horo->hunger -= $feedVal;
        $horo->save();

        // Feed will increase the love_degree
        $user->food_contrib += $feedVal;
        $user->affection += $feedVal / 2.0;
        $user->update();

        return $feedVal;
    }

    /**
     * Action Performed when horo is taught
     *
     * @param User $user the user Object
     * @param Horo $horo the horo Object
     * @return $feedVal int
     */
    public static function Teach(User $user, Horo $horo) {

    }

    /**
     * Action Performed when horo is cleaned up
     *
     * @param User $user the user Object
     * @param Horo $horo the horo Object
     * @return $feedVal int
     */
    public static function Wash(User $user, Horo $horo) {

    }

}