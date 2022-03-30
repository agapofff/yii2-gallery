<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="text-center">
    <?= Html::img(Yii::$app->urlManager->hostInfo . '/images/store/' . $model->filePath, [
        'class' => 'img-thumbnail'
    ]) ?>
</div>
<br>

<?php 
    $form = ActiveForm::begin([
        'action' => [
            '/gallery/default/write',
            'id' => $model->id
        ],
        'options' => [
            'id' => 'yii2gallery-form'
        ]
    ]);
?>

    <?= $form
            ->field($model, 'title')
            ->textInput([
                'maxlength' => 255
            ])
    ?>

    <?= $form
            ->field($model, 'alt')
            ->textInput([
                'maxlength' => 255
            ])
    ?>

    <?= $form
            ->field($model, 'description')
            ->textInput([
                'maxlength' => 255
            ])
    ?>

    <?= $form
            ->field($model, 'url')
            ->textInput([
                'maxlength' => 255
            ])
    ?>
    
    <?= $form
            ->field($model, 'newPage')
            ->checkbox();
    ?>

    <?= $form
            ->field($model, 'sort')
            ->hiddenInput()
            ->label(false)
    ?>

    <?= Html::hiddenInput('model', $post['model']) ?>

    <?= Html::hiddenInput('id', $post['id']) ?>

    <?= Html::hiddenInput('image', $post['image']) ?>

    <br>
    <div class="text-center">
        <?= Html::submitButton(Yii::t('gallery', 'Save'), [
            'class' => 'btn btn-primary'
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>