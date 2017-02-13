<?php

namespace DevHoro;

class Server {

    const version = '0.0.0';

    static function getMachineID($request) {
        return $request->getHeaderLine('X-Lawrence-ID');
    }

}
