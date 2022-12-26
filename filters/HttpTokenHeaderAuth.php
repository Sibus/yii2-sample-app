<?php

namespace app\filters;

use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

class HttpTokenHeaderAuth extends ActionFilter
{
    public function __construct(
        private readonly string $header,
        private readonly string $token,
        $config = [],
    )
    {
        parent::__construct($config);
    }

    /**
     * @throws UnauthorizedHttpException
     */
    public function beforeAction($action): bool
    {
        $request = \Yii::$app->request;
        $authHeader = $request->getHeaders()->get($this->header);
        if ($authHeader === null || $authHeader !== $this->token) {
            throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
        }
        return true;
    }
}
