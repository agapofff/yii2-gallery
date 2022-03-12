<?php
namespace dvizh\gallery\widgets;

use yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\jui\Sortable;
use kartik\file\FileInput;
use dvizh\gallery\assets\GalleryAsset;

class Gallery extends \yii\base\Widget
{
    public $model = null;
    public $previewSize = '140x140';
    public $fileInputPluginLoading = true;
    public $fileInputPluginOptions = [];
    public $label = null;
    public $labelClass = 'control-label';
    public $containerClass = 'row';
    public $elementClass = 'col-xs-12 col-md-6';
    public $imageClass = 'img-thumbnail img-fluid';
    public $deleteButtonText = 'Delete';
    public $deleteButtonClass = 'btn btn-danger';
    public $editButtonText = 'Change';
    public $editButtonClass = 'btn btn-info';
    public $deleteConfirmText = 'Delete image?';
    public $hint = null;
    public $hintClass = null;
 
    public function init()
    {
        $view = $this->getView();
        $view->on($view::EVENT_END_BODY, function ($event) {
            echo $this->render('modal');
        });

        GalleryAsset::register($this->getView());
    }

    public function run()
    {
        $model = $this->model;
        $params = [];
        $img = '';
        
        if ($this->label) {
            $label = Html::tag('label', $this->label, [
                'class' => $this->labelClass
            ]);
        }
        
        $gallery = '';
        
        if ($model->getGalleryMode() == 'single') {
            if ($model->hasImage()) {
                $image = $this->model->getImage();
                $img = $this->getImagePreview($image);
                $params = $this->getParams($image->id);
            }

            return $label . Html::tag('div', $img, $params) . $this->getFileInput();
        }

        if ($this->model->hasImage()) {
            $elements = $this->model->getImages();
            $items = [];
            
            foreach ($elements as $element) {
                $items[] = $this->row($element);
            }
            
            $gallery = Sortable::widget([
                    'items' => $items,
                    'options' => [
                        'tag' => 'div',
                        'class' => 'dvizh-gallery ' . $this->containerClass,
                        'data' => [
                            'action' => Url::toRoute(['/gallery/default/sort'])
                        ]
                    ],
                    'itemOptions' => [
                        'tag' => 'div',
                        'class' => $this->elementClass,
                    ],
                    'clientOptions' => [
                        'cursor' => 'move',
                        'update' => new JsExpression('yii2gallery.setSort'),
                    ],
                ]);
        }
        
        $hint = $this->hint ? Html::tag('div', $this->hint, [
            'class' => $this->hintClass
        ]) : null;

        return Html::tag('div', $label . $gallery . $this->getFileInput() . $hint);
    }

    private function row($image)
    {
        if ($image instanceof \dvizh\gallery\models\PlaceHolder) {
            return '';
        }

        $class = 'dvizh-gallery-row';

        if ($image->isMain) {
            $class .= ' main';
        }

        $liParams = $this->getParams($image->id);
        $liParams['class'] .= $class;

        return Html::tag('div', $this->getImagePreview($image), $liParams);
    }

    private function getFileInput()
    {
        return FileInput::widget([
            'name' => $this->model->getInputName() . '[]',
            'options' => [
                'accept' => 'image/*',
                'multiple' => $this->model->getGalleryMode() == 'gallery',
            ],
            'pluginOptions' => $this->fileInputPluginOptions,
            'pluginLoading' => $this->fileInputPluginLoading
        ]);
    }

    private function getParams($id)
    {
        $model = $this->model;

        return  [
            'class' => 'dvizh-gallery-item ',
            'data-model' => $model::className(),
            'data-id' => $model->id,
            'data-image' => $id
        ];
    }

    private function getImagePreview($image)
    {
        $size = (explode('x', $this->previewSize));

        $delete = Html::a($this->deleteButtonText, '#', [
            'data-action' => Url::toRoute([
                '/gallery/default/delete',
                'id' => $image->id
            ]),
            'data-confirm' => $this->deleteConfirmText,
            'class' => 'delete ' . $this->deleteButtonClass,
        ]);
        
        $write = Html::a($this->editButtonText, '#', [
            'data-action' => Url::toRoute([
                '/gallery/default/modal',
                'id' => $image->id
            ]), 
            'class' => 'write ' . $this->editButtonClass,
        ]);
        
        $img = Html::img($image->getUrl($this->previewSize), [
            'data-action' => Url::toRoute([
                '/gallery/default/setmain',
                'id' => $image->id
            ]),
            'width' => $size[0],
            'height' => $size[1],
            'class' => $this->imageClass,
        ]);
        
        $a = Html::a($img, $image->getUrl());

        return $a . $delete . $write;
    }
}
