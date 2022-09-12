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
                'mode' => 'gallery',        // or 'single' - one image
                'quality' => 60,            // percentage of image quality compression
                'galleryId' => 'gallery'    // use this unique options for resolving conflicts the same class names
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
            'previewSize' => '140x140',                 // uploaded images preview size
            'containerClass' => 'row',                  // gallery container class
            'elementClass' => 'col-xs-12 col-md-6',     // image container class
            'imageClass' => 'img-thumbnail img-fluid',  // image class
            'deleteButtonText' => 'Delete',             // delete button content. HTML allowed
            'deleteButtonClass' => 'btn btn-danger',    // delete button class
            'deleteConfirmText' => 'Delete image?',     // delete confirmation alert message
            'editButtonText' => 'Change',               // edit button content. Html allowed
            'editButtonClass' => 'btn btn-info',        // edit button class
            'hint' => null,                             // hint message under the gallery
            'hintClass' => null,                        // hint message class
            'fileInputPluginLoading' => true,           // show uploading progress indicator
            'fileInputPluginOptions' => [],             // settings for Kartik Fileinput plugin http://demos.krajee.com/widget-details/fileinput
        ]);
    ?>
```

Getting attached images params

```php
    $image = $model->getImage();    // get attached image in single mode
    $images = $model->getImages();  // get all attached images in gallery mode

    $image->getUrl();               // url to full image
    $image->getUrl('300x');         // url to proportionally resized image by width
    $image->getUrl('x300');         // url to proportionally resized image by height
    $image->getUrl('200x300');      // url to resized and cropped (centered) image by width and height
    
    $image->getPath();              // filepath to original image
    $image->getPath('50x50');       // filepath to sized image
    
    $image->getContent();           // content of original image file
    $image->getContent('100x');     // content of sized image file
    
    $image->getSizes();             // array of generated image sizes: ['width' => 1000, 'height' => 500]
    $image->getSizesWhen('100x');   // array of generated image sizes with condition
    
    $image->alt;                    // image alt attribute
    $image->title;                  // image title
    $image->description;            // image description
    $image->gallery_id;             // unique gallery name (if set)
    $image->url;                    // link for image
    $image->newPage;                // open link in new page
    $image->isMain;                 // is this image is main in gallery
```


based on [dvizh/yii2-gallery](https://github.com/dvizh/yii2-gallery)