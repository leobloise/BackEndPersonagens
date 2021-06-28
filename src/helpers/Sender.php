<?php

namespace leona\crud\helpers;

use Requests;

trait Sender 
{
    public function send(string $destiny)
    {
        $request = Requests::get($destiny);
        return $request->body;
    }

}