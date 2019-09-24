<?php

namespace App;

class Request extends AbstractModel
{
    public $id;
    public $name;
    public $phone;
    public $message;

    protected static $table = 'request';
    protected $saveStrategy = 'db';

    protected $dbFields = [
        'name' => 'string',
        'phone' => 'string',
        'message' => 'string',
    ];

    public function save()
    {
        /** @var $strategy RequestSaveInterface */
        $strategy = RequsetSaveStrategyFactory::getStrategy($this->saveStrategy);
        $strategy->save($this);
    }

    public function saveInDb()
    {
        parent::save();
    }

    public function saveInFile()
    {
        $name = uniqid() . '.txt';
        $data = <<<DATA
Имя: $this->name
Телефон: $this->phone
Сообщение: $this->message
DATA;
        file_put_contents('tmp/' . $name, $data);
    }

    /**
     * @param string $saveStrategy
     */
    public function setSaveStrategy($saveStrategy)
    {
        $this->saveStrategy = $saveStrategy;
    }
}