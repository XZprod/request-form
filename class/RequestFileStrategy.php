<?php

namespace App;

class RequestFileStrategy implements RequestSaveInterface
{

    public function save(Request $request)
    {
        $request->saveInFile();
    }
}