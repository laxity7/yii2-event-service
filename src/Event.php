<?php

namespace Laxity7\Yii2\Components;

/**
 * Helper for event dispatch.
 */
class Event extends \yii\base\Event
{
    public static function dispatch(object $event): void
    {
        \Yii::$app->eventDispatcher->dispatch($event);
    }
}
