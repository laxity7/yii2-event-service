<?php

namespace Laxity7\Yii2\Components;

use Yii;
use yii\base\BaseObject;

/**
 * This class is a service provider for events. It allows to globally subscribe to events and call methods/functions when an event occurs.
 *
 * Add to application configuration:
 *
 * ```php
 * 'components' => [
 *     'eventDispatcher' => [
 *         'class' => \Laxity7\Yii2\Components\EventServiceProvider::class,
 *         'listen' => [
 *            \App\Events\PaymentEvent::class => [
 *                \App\Events\listeners\PaymentListener::class,
 *                function (\App\Events\PaymentEvent $event) {
 *                    Yii::info('Event: ' . get_class($event) . ' Trigger: ' . __METHOD__, __METHOD__);
 *                },
 *            ],
 *         ],
 *     ],
 * ],
 * ```
 *
 * To trigger an event:
 * ```php
 * \Laxity7\Yii2\Components\Event::dispatch(new \App\Events\PaymentEvent());
 * ```
 */
class EventServiceProvider extends BaseObject
{
    /**
     * List of events and their listeners.
     *
     * Where:
     *  - key - Event class
     *  - value - list of classes/methods that will be called upon the event
     * If the value is a class, then the handle/__invoke method will be called with the $event parameter.
     * If the value is a method, then that method will be called with the $event parameter.
     * $event - an instance of the event class specified in the key.
     *
     * For example:
     *
     * ```php
     * [
     *     \app\events\PaymentEvent::class => [
     *         \app\listeners\PaymentListener::class,
     *         function (\app\events\PaymentEvent $event) {
     *             Yii::info('Event: ' . get_class($event) . ' Trigger: ' . __METHOD__, __METHOD__);
     *         },
     *     ],
     * ]
     * ```
     * @var array<class-string, list<class-string|\Closure>>
     */
    public array $listen = [];
    /**
     * Whether to log events.
     */
    public bool $logEvents = true;

    /**
     * Dispatches an event.
     *
     * @param object $event
     */
    public function dispatch(object $event): void
    {
        $listeners = $this->listen[get_class($event)] ?? [];
        if (empty($listeners)) {
            return;
        }

        foreach ($listeners as $listener) {
            $this->fire($event, $listener);
        }
    }

    /**
     * Fires an event.
     *
     * @param object $event
     * @param \Closure|object $listener
     */
    private function fire(object $event, $listener): void
    {
        $this->log($event, $listener);

        if (is_callable($listener)) {
            call_user_func($listener, $event);

            return;
        }

        $listener = Yii::createObject($listener);
        if (method_exists($listener, 'handle')) {
            $listener->handle($event);

            return;
        }

        throw new \InvalidArgumentException('Listener must be a class with handle method or a closure');
    }

    /**
     * Logs the event.
     *
     * @param object $event
     * @param \Closure|object $listener
     */
    private function log(object $event, $listener): void
    {
        if (!$this->logEvents) {
            return;
        }
        $listener = is_callable($listener) ? 'closure' : $listener;
        Yii::info('Event: ' . get_class($event) . PHP_EOL . 'Trigger: ' . $listener);
    }
}
