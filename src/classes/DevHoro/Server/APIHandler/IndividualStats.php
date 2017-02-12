<?php

namespace DevHoro\Server\APIHandler;

class IndividualStats extends Base {

  const name = 'individual/stats';
  const description = 'Get stats of a user.';
  const params = [
    'user_id' => 'Unique ID of the user.'
  ];

  protected $user_id;
  protected $result_array;

  function processRequest ($request) {
    $this->user_id = intval($this->options['user_id']);
    #$this->result_array = \DevHoro\Server\Database::getUserStats([
    #  'user_id' => $this->user_id
    #]);
    $this->result_array = [
      'user_id' => $this->user_id,
      'coins' => 100,
      'affection' => 0,
    ];
  }

  function getResponse ($response) {
    return $response->withJson($this->result_array);
  }

}
