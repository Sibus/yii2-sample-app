<?php

declare(strict_types=1);

namespace app\bootstrap;

use yii\base\BootstrapInterface;

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
    }
}
