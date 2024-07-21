<?php

namespace Laxity7\ii2\components;

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
