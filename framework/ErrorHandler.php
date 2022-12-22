<?php

declare(strict_types=1);

namespace app\framework;

use app\services\EntityNotFoundException;
use yii\web\NotFoundHttpException;

class ErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * @throws NotFoundHttpException
     */
    public function handleException($exception)
    {
        if ($exception instanceof EntityNotFoundException) {
            $this->logException($exception);
            $exception = new NotFoundHttpException($exception->getMessage(), previous: $exception);
        }
        if ($exception instanceof \DomainException) {
            $this->logException($exception);
            $exception = new \yii\web\UnprocessableEntityHttpException($exception->getMessage(), previous: $exception);
        }
        parent::handleException($exception);
    }
}
