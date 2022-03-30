<?php
namespace agapofff\gallery;

use Yii;
use yii\base\Exception;

trait ModuleTrait
{
    private $_module;

    protected function getModule()
    {
        if ($this->_module == null) {
            $this->_module = Yii::$app->getModule('gallery');
        }

        if (!$this->_module) {
            throw new Exception('Gallery module not found');
        }

        return $this->_module;
    }
}