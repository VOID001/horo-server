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
  ];

  static function getAllApis () {
    return self::allApis;
  }

}
