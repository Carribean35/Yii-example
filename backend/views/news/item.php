<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use vova07\imperavi\Widget;
use common\components\Images;
use common\models\News;

if (empty($model->id)) {
  $this->title = $this->context->title_h3 = 'Добавить';
} else {
  $this->title = $model->name_ru;
  $this->context->title_h3 = 'Редактировать';
}

$this->params['breadcrumbs'][] = ['url' => Url::toRoute(['index']), 'label' => 'Новости'];
$this->params['breadcrumbs'][] = $this->title;

$this->context->menuActiveItems[backend\controllers\BController::NEWS_MENU_ITEM] = 1;
$this->context->menuActiveItems[backend\controllers\BController::CONTENT_MENU_ITEM] = 1;

$this->registerJsFile('/js/lightbox/js/lightbox.min.js');
$this->registerCssFile('/js/lightbox/css/lightbox.min.css');

?>

<div>

  <?php
  $img = '';
  $tmp = Images::getImage(News::getImagePath(), News::getImageUrl(), $model->image);
  if (!empty($tmp)) {
    $tmp .= '?' . mt_rand('1', 10000);
    $img  = '<a href="' . $tmp . '" data-lightbox="roadtrip">
                            <img src="' . $tmp . '" width="150">
                        </a>';
  }
  $form = ActiveForm::begin([
    'id' => 'item-form',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute('validate'),
    'options' => ['class' => 'form form-horizontal'],
    'fieldConfig' => [
      'template' => '{label}<div class="col-md-10">{input}<span class="help-block">{error}</span></div>',
      'labelOptions' => ['class' => 'col-md-2 control-label'],
    ],
  ]) ?>
  <div class="form-body">

    <?= $form->field($model, 'id', ['template' => '{input}', 'options' => ['class' => '']])->hiddenInput() ?>

    <?= $form->field(
      $model,
      'image_input',
      [
        'template' => '{label}
								<div class="col-md-10">
                                    ' . $img . '
									{input}
									{error}
								</div>',
      ]
    )->fileInput(); ?>
    <hr />

    <?= $form->field($model, 'name_ru') ?>
    <?= $form->field($model, 'short_name_ru') ?>
    <?= $form->field($model, 'text_ru')->widget(Widget::class, [
      'settings' => [
        'buttons' => ['html', 'bold', 'italic', 'alignment', 'unorderedlist', 'orderedlist', 'image', 'link',],
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Url::to(['image-upload']),
        'imageManagerJson' => Url::to(['images-get']),
        'plugins' => [
          'imagemanager',
        ],
      ],
    ]);
    ?>
    <?= $form->field($model, 'short_text_ru')->textarea() ?>

    <?= $form->field($model, 'title_ru') ?>
    <?= $form->field($model, 'keywords_ru')->textarea(['class' => 'form-control ']); ?>
    <?= $form->field($model, 'description_ru')->textarea(['class' => 'form-control ']); ?>

    <hr />

    <?= $form->field($model, 'name_us') ?>
    <?= $form->field($model, 'short_name_us') ?>
    <?= $form->field($model, 'text_us')->widget(Widget::class, [
      'settings' => [
        'buttons' => ['html', 'bold', 'italic', 'alignment', 'unorderedlist', 'orderedlist', 'image', 'link',],
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Url::to(['image-upload']),
        'imageManagerJson' => Url::to(['images-get']),
        'plugins' => [
          'imagemanager',
        ],
      ],
    ]);
    ?>
    <?= $form->field($model, 'short_text_us')->textarea() ?>

    <?= $form->field($model, 'title_us') ?>
    <?= $form->field($model, 'keywords_us')->textarea(['class' => 'form-control ']); ?>
    <?= $form->field($model, 'description_us')->textarea(['class' => 'form-control ']); ?>

    <hr />

    <?= $form->field($model, 'name_tr') ?>
    <?= $form->field($model, 'short_name_tr') ?>
    <?= $form->field($model, 'text_tr')->widget(Widget::class, [
      'settings' => [
        'buttons' => ['html', 'bold', 'italic', 'alignment', 'unorderedlist', 'orderedlist', 'image', 'link',],
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Url::to(['image-upload']),
        'imageManagerJson' => Url::to(['images-get']),
        'plugins' => [
          'imagemanager',
        ],
      ],
    ]);
    ?>
    <?= $form->field($model, 'short_text_tr')->textarea() ?>

    <?= $form->field($model, 'title_tr') ?>
    <?= $form->field($model, 'keywords_tr')->textarea(['class' => 'form-control ']); ?>
    <?= $form->field($model, 'description_tr')->textarea(['class' => 'form-control ']); ?>

    <hr />

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'date')->textInput(['class' => 'form-control mask_date']) ?>

    <?= $form->field($model, 'visible', ['labelOptions' => ['class' => 'col-md-2 control-label'], 'template' => '<label class="col-md-2 control-label">Видимость</label> <div class="col-md-10"><div class="mt-checkbox-inline">{input}</div></div>'])->checkbox([
      'label' => '<span></span>',
      'labelOptions' => [
        'class' => 'mt-checkbox mt-checkbox-outline'
      ],
    ]) ?>
  </div>

  <div class="form-actions">
    <div class="row">
      <div class="col-md-offset-2 col-md-10">
        <?= Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class' => 'btn green']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Удалить', Url::toRoute(['delete', 'id' => $model->id]), ['class' => 'btn red confirm-delete']) ?>
      </div>
    </div>
  </div>
  <?php ActiveForm::end() ?>

</div>