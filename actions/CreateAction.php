<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use Yii;


/**
 * Class CreateAction
 * @package voskobovich\admin\actions
 */
class CreateAction extends BaseAction
{
    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectRoute = 'update:id';

    /**
     * View file
     * @var string
     */
    public $viewFile = 'create';

    /**
     * @return string
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $params = Yii::$app->request->post();

        if ($model->load($params)) {
            if ($model->save()) {
                if ($this->successCallback) {
                    call_user_func($this->successCallback, $model);
                } else {
                    Yii::$app->session->setFlash('create:success');
                }
                $this->redirect($model);
            } else {
                if ($this->errorCallback) {
                    call_user_func($this->errorCallback, $model);
                } else {
                    Yii::$app->session->setFlash('create:error');
                }
            }
        }

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model
        ]);
    }
}