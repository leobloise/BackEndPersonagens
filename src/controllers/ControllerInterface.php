<?php

namespace leona\crud\controllers;

require_once 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface {

    public function processRequest(ServerRequestInterface $request): ResponseInterface;

}