<?php

namespace DevHoro\Server\APIHandler;

class Base {

  const name = 'base';
  const description = 'Description goes here.';
  const params = [];

  public $options;

  function __construct ($options) {
    $this->options = $options;
  }

  function processRequest ($request) {}

  function getResponse ($response) {
    return $response->getBody()->write('OK.');
  }

}
