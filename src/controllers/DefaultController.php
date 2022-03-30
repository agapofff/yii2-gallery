<?php
namespace agapofff\gallery\controllers;

use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use agapofff\gallery\models\Image;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'ajax' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles
                    ]
                ]
            ]
        ];
    }

    
    public function actionIndex()
    {
        return $this->render('index');
    }
            
    public function actionModal($id)
    {
        $model = $this->findImage($id);
        
        return $this->renderAjax('modalAdd', [
            'model' => $model,
            'post' => Yii::$app->request->post(),
        ]);
    }

    public function actionWrite($id)
    {
        $model = $this->findImage($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->returnJson('success');
        }
        
        return $this->returnJson('false', Yii::t('gallery', 'Model or Image not found'));
    }

    public function actionDelete($id)
    {
        $model = $this->findImage($id);
        $model->delete();
        
        return $this->returnJson('success');
    }
    
    public function actionSetmain($id)
    {
        $model = $this->findImage($id);
        Yii::$app->db->createCommand('UPDATE {{%images}} SET isMain = 0 WHERE itemId = :itemId')
            ->bindValue(':itemId', $model->itemId)
            ->execute();
        $model->isMain = 1;
        $model->save();
        
        return $this->returnJson('success');
    }
    
    public function actionSort()
    {
        $items = Yii::$app->request->post('items');
        for ($i = 0; $i < count($items); $i++) {
            $model = $this->findImage($items[$i]);
            $model->sort = $i;
            $model->save();
        }
        return $this->returnJson('success');
    }
    
    private function returnJson($result, $error = false)
    {
        $json = [
            'result' => $result,
            'error' => $error
        ];
        
        return Json::encode($json);
    }

    protected function findImage($id)
    {
        if (!$model = Image::findOne($id)) {
            throw new NotFoundHttpException('Image not found');
        }
        
        return $model;
    }
}