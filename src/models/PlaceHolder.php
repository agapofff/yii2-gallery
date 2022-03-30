<?php
namespace agapofff\gallery\models;

use Yii;
use yii\base\Exception;

class PlaceHolder extends Image
{
    private $modelName = '';
    private $itemId = '';
    public $filePath = 'placeHolder.png';
    public $urlAlias = 'placeHolder';

    public function __construct()
    {
        $this->filePath = basename(Yii::getAlias($this->getModule()->placeHolderPath)) ;
    }

    public function getPathToOrigin()
    {
        $url = Yii::getAlias($this->getModule()->placeHolderPath);
        
        if (!$url) {
            throw new Exception(Yii::t('app', 'PlaceHolder image must have path setting'));
        }
        
        return $url;
    }

    protected  function getSubDur()
    {
        return 'placeHolder';
    }
    
    public function setMain($isMain = true)
    {
        throw new Exception(Yii::t('app', 'You must not set placeHolder as main image'));
    }
}

