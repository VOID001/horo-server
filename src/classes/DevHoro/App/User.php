<?php

namespace DevHoro\App;

use Illuminate\Database\Eloquent\Model;



// Compare the favor score

class User extends Model
{
    protected $table = 'user_info';
    protected $primaryKey = 'machine_id';


    static function cmp($a, $b) {
        return ($a->score() <= $b->score()) ? 1 : -1;
    }

    private function score() {
        return $this->affection*0.2 + $this->food_contrib*0.4 + $this->knowlege_contrib*0.2 + $this->clean_contrib*0.2;
    }

    public static function TopNFavoriteUser($n) {
        $users = User::all();
        $userarr = [];
        $data = [];
        foreach($users as $key => $val) {
            array_push($userarr, $val);
        }
        usort($userarr, ["DevHoro\App\User", "cmp"]);

        for($i = 0; $i < min($n, count($userarr)); $i++) {
            $data[$i] = [
                "排名" => $i + 1,
                "ID" => substr($userarr[$i]->attributes["machine_id"], 0, 7),
                "主(shi)人(wu)昵称" => $userarr[$i]->user_name,
                "亲密度" => $userarr[$i]->affection,
                "总友好度" => $userarr[$i]->score(),
            ];
        }

        return $data;
    }
}
