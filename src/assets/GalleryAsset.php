<?php
namespace agapofff\gallery\assets;

use yii\web\AssetBundle;

class GalleryAsset extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
    ];
    
    public $js = [
        'js/scripts.js',
    ];
    
    public $css = [
        'css/style.css',
    ];
    
    public function init()
    {
        $this->sourcePath = dirname(__DIR__) . '/web';
        parent::init();
    }
}
