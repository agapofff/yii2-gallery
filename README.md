Yii2-gallery
==========

This extension allows you to:
* attach images to any model
* fill titles and alt attributes of those images
* change images order by simple drag-n-drop
* wrap every image to it's own link

Installation
---------------------------------

### With [composer](http://getcomposer.org/download/)

Run 

```
composer require agapofff/yii2-gallery "*"
```

or add

```
"agapofff/yii2-gallery": "*",
```

to composer.json and run

```
php composer update
```

Use migration

```
php yii migrate/up --migrationPath=@vendor/agapofff/yii2-gallery/src/migrations
```

Settings
---------------------------------

Add to config file
```php
    'modules' => [
        //...
        'gallery' => [
            'class' => 'agapofff\gallery\Module',
            'imagesStorePath' => dirname(dirname(__DIR__)).'/frontend/web/images/store', //path to origin images
            'imagesCachePath' => dirname(dirname(__DIR__)).'/frontend/web/images/cache', //path to resized copies
            'graphicsLibrary' => 'GD', // or Imagick
            'placeHolderPath' => '@webroot/images/placeHolder.png', // path to placeholder image
            'adminRoles' => ['admin', 'manager'], // use '@' to allow authorized users attach images on frontend
        ],
        //...
    ]
```

Add behaviour to the model to which you want to attach images

```php
    function behaviors()
    {
        return [
            'images' => [
                'class' => 'agapofff\gallery\behaviors\AttachImages',
                'mode' => 'gallery', // or 'single' - one image
                'quality' => 60, // percentage of image quality compression
                'galleryId' => 'gallery' // use this unique options for resolving conflicts the same class names
            ],
        ];
    }
```

Add ```enctype="multipart/form-data"``` to you model form

```php
    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
```

Add the widget for uploading images in your form

```php
    <?= \agapofff\gallery\widgets\Gallery::widget([
            'model' => $model,
            'previewSize' => '140x140', // uploaded images preview size
            'containerClass' => 'row', // gallery container class
            'elementClass' => 'col-xs-12 col-md-6', // image container class
            'imageClass' => 'img-thumbnail img-fluid', // image class
            'deleteButtonText' => 'Delete', // delete button content. HTML allowed
            'deleteButtonClass' => 'btn btn-danger', // delete button class
            'deleteConfirmText' => 'Delete image?', // delete confirmation alert message
            'editButtonText' => 'Change', // edit button content. Html allowed
            'editButtonClass' => 'btn btn-info', // edit button class
            'hint' => null, // hint message under the gallery
            'hintClass' => null, // hint message class
            'fileInputPluginLoading' => true, // show uploading progress indicator
            'fileInputPluginOptions' => [], // settings for Kartik Fileinput plugin http://demos.krajee.com/widget-details/fileinput
        ]);
    ?>
```

Getting attached images params

```php
    $images = $model->getImages();
    foreach ($images as $image) {
        echo $image->getUrl(); // url to full image
        echo $image->getUrl('300x'); // url to proportionally resized image by width
        echo $image->getUrl('x300'); // url to proportionally resized image by height
        echo $image->getUrl('200x300'); // url to resized and cropped (centered) image by width and height
        echo $image->alt; // image alt attribute
        echo $image->title; // image title
        echo $image->description; // image description
        echo $image->description; // image description
        echo $image->url; // link for image
        echo $image->newPage; // open link in new page
    }
```


based on [dvizh/yii2-gallery](https://github.com/dvizh/yii2-gallery)