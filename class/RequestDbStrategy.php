<?php

namespace App;

class RequestDbStrategy implements RequestSaveInterface
{

    public function save(Request $request)
    {
        $request->saveInDb();
    }
}