<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = $this->context->title_h3 = 'Новости';

$this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs_buttons'][] = '<a href="' . Url::toRoute(['item']) . '" class="btn green btn-sm"><i class="fa fa-plus"></i> Добавить</a>';

$this->context->menuActiveItems[backend\controllers\BController::NEWS_MENU_ITEM] = 1;
$this->context->menuActiveItems[backend\controllers\BController::CONTENT_MENU_ITEM] = 1;
?>

<div>
  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
      'class' => 'table table-striped table-bordered table-hover',
      'id' => 'sample_1',
    ],
    'layout' => "{items}<div class='text-center'>{pager}</div>",
    'columns' => [
      [
        'class' => 'yii\grid\SerialColumn',
        'headerOptions' => ['width' => '1%'],
      ],
      [
        'attribute' => 'name_ru',
        'label' => 'Название',
        'enableSorting' => false,
        'format' => 'raw',
        'value' => function ($model) {
          $inp = '<input type="hidden" class="row_id" value="' . $model->id . '">';
          return $inp . $model->name_ru;
        },
      ],
      [
        'attribute' => 'date',
        'label' => 'Дата',
        'enableSorting' => false,
      ],
      [
        'attribute' => 'visible',
        'label' => 'Видимость',
        'enableSorting' => false,
        'headerOptions' => ['width' => '1%'],
        'format' => 'html',
        'value' => function ($model) {
          return !empty($model->visible) ? '<span class="btn btn-xs green">видимый</span>' : '<span class="btn btn-xs red">скрытый</span>';
        }
      ],
      [
        'class' => 'yii\grid\ActionColumn',
        'header' => 'Действия',
        'headerOptions' => ['width' => '1%'],
        'template' => '{item} {delete}',
        'contentOptions' => ['class' => 'text-nowrap'],
        'buttons' => [
          'item' => function ($url) {
            return Html::a(
              '<i class="fa fa-edit"></i> Редактировать',
              $url,
              ['class' => 'btn btn-xs green']
            );
          },
          'delete' => function ($url) {
            return Html::a(
              '<i class="fa fa-trash"></i> Удалить',
              $url,
              ['class' => 'btn btn-xs red confirm-delete']
            );
          },
        ],
      ],
    ],
  ]); ?>
</div>