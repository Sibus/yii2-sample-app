<?php

declare(strict_types=1);

namespace app\bootstrap;

use app\dispatchers\AsyncEventDispatcher;
use app\dispatchers\EventDispatcher;
use app\dispatchers\EventDispatcherInterface;
use app\jobs\AsyncEventJobHandler;
use app\listeners\BookListener;
use app\services\BookCreatedEvent;
use app\services\BookRatedEvent;
use yii\base\BootstrapInterface;
use yii\di\Container;
use yii\di\Instance;
use yii\queue\Queue;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = \Yii::$container;

        $container->setSingleton(\Elastic\Elasticsearch\Client::class, function () {
            $hosts = explode(',', env('ES_HOSTS', ''));
            $builder = \Elastic\Elasticsearch\ClientBuilder::create();
            if ($hosts) {
                $builder->setHosts($hosts);
            }
            return $builder->build();
        });

        $container->setSingleton(Queue::class, fn() => $app->get('queue'));

        $container->setSingleton(EventDispatcherInterface::class, AsyncEventDispatcher::class);

        $container->setSingleton(EventDispatcher::class, function (Container $container) {
            $dispatcher = new EventDispatcher($container);
            $dispatcher->addListener(BookRatedEvent::class, BookListener::class);
            $dispatcher->addListener(BookCreatedEvent::class, BookListener::class);
            return $dispatcher;
        });

        $container->setSingleton(AsyncEventJobHandler::class, [], [
            Instance::of(EventDispatcher::class),
        ]);
    }
}
