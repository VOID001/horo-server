<?php

namespace DevHoro\Server;

class APIHandler {

    const allApis = [
        'version' => [
            'class_name' => 'Version',
            'methods' => ['GET']
        ],
        'individual/stats' => [
            'class_name' => 'IndividualStats',
            'methods' => ['GET']
        ],
        'horo/feed' => [
            'class_name' => 'FeedHoro',
            'methods' => ['GET', 'POST']
        ],
        'individual/nick' => [
            'class_name' => 'NickRegister',
            'methods' => ['POST']
        ]
    ];

    static function getAllApis () {
        return self::allApis;
    }

}
