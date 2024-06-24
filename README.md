# Event service for Yii2

[![License](https://img.shields.io/github/license/laxity7/yii2-event-service.svg)](https://github.com/laxity7/yii2-event-service/blob/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/laxity7/yii2-event-service.svg)](https://packagist.org/packages/laxity7/yii2-event-service)
[![Total Downloads](https://img.shields.io/packagist/dt/laxity7/yii2-event-service.svg)](https://packagist.org/packages/laxity7/yii2-event-service)

Yii2 events provide a simple observer pattern implementation, allowing you to subscribe and listen for various events that occur within your application.

## Install

Install via composer 

```shell
composer require laxity7/yii2-event-service
```

## How to use


### 1. Create an event class

It will be an any class that contains event data. You can also use the default Yii2 event class `yii\base\Event`.

For example:
```php
namespace App\Events;

use yii\base\Event;

final readonly class PaymentEvent
{
    public function __construct(
        public float $amount,
        public string $currency,
        public string $description,
    ) {
    }
}
```

### 2. Create a listener class

It will be a class that contains a method `handle` or `__invoke` that will be called when the event is dispatched. The method must accept an event object as an argument.

For example:
```php
namespace App\Events\listeners;

use App\Events\PaymentEvent;

final class PaymentListener
{
    //public function __invoke(PaymentEvent $event): void
    public function handle(PaymentEvent $event): void
    {
        Yii::info('Event: ' . get_class($event) . ' Trigger: ' . __METHOD__, __METHOD__);
    }
}
```

> Note: You can also use a closure as a listener. The closure must accept an event object as an argument.

### 3. Subscribe to the event

Add the following code to your configuration file:

```php
'components' => [
    'eventDispatcher' => [
         'class' => \Laxity7\Yii2\Components\EventServiceProvider::class,
         'listen' => [
             \App\Events\PaymentEvent::class => [
                 \App\Events\listeners\PaymentListener::class, // listener class
                 function (\App\Events\PaymentEvent $event) { // closure
                    Yii::info('Event: ' . get_class($event) . ' Trigger: ' . __METHOD__, __METHOD__);
                 },
             ],
         ],
    ],
],
```

### 4. Dispatch the event

```php
use App\Events\PaymentEvent;
use Laxity7\Yii2\Components\Event;

$event = new PaymentEvent(100, 'USD', 'Payment for goods');
\Yii::$app->eventDispatcher->dispatch($event);
// or use the helper
Event::dispatch($event);
```
