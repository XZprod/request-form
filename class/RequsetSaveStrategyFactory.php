<?php

namespace App;

class RequsetSaveStrategyFactory
{
    const DEFAULT_STRATEGY = 'db';

    protected static $strategies = [
        'db' => RequestDbStrategy::class,
        'file' => RequestFileStrategy::class,
    ];

    public static function getStrategy($strategyName = self::DEFAULT_STRATEGY)
    {
        if (array_key_exists($strategyName, self::$strategies)) {
            return new self::$strategies[$strategyName];
        }
        throw new \InvalidArgumentException("");
    }
}