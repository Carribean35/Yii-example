<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use common\components\Images;
use common\helpers\Functions;
use common\models\News;

class NewsController extends FController
{
  private $limit = 8;

  public function actionIndex()
  {
    return $this->render('/site/index');
  }

  public function actionView($id)
  {
    $data = News::findOne(['url' => $id, 'visible' => true]);
    if (empty($data)) {
      throw new NotFoundHttpException();
    }

    if (!empty($data->title_ru)) {
      $this->variables['title'] = $data->title_ru;
    }
    if (!empty($data->description_ru)) {
      $this->variables['description'] = $data->description_ru;
    }
    if (!empty($data->keywords_ru)) {
      $this->variables['keywords'] = $data->keywords_ru;
    }

    return $this->render('/site/index');
  }

  public function actionGetList()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $params = json_decode(Yii::$app->getRequest()->getRawBody(), true);
    $lang = Functions::getFrontLang();

    $ret = ['pages' => 0, 'list' => []];
    if (Yii::$app->request->isPost) {
      $models = News::find()->where(['visible' => true])->orderBy('date DESC')->limit($this->limit)->offset($this->limit * intval($params['page']))->all();

      foreach ($models as $v) {
        $ret['list'][] = [
          'id' => $v->id,
          'short_name' => $v->{'short_name_' . $lang},
          'date' => $v->date,
          'url' => $v->url,
          'img' => Images::getImage(News::getImagePath(), News::getImageUrl(), $v->image),
        ];
      }

      $count = News::find()->where(['visible' => true])->count();
      $ret['pages'] = ceil($count / $this->limit);
    }

    return $ret;
  }

  public function actionGetItem()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $params = json_decode(Yii::$app->getRequest()->getRawBody(), true);
    $lang = Functions::getFrontLang();

    if (Yii::$app->request->isPost) {
      $model = News::findOne(['url' => $params['id'], 'visible' => true]);
      if (empty($model)) {
		return [];
	  }

      return [
        'url' => $model->url,
        'name' => $model->{'name_' . $lang},
        'text' => $model->{'text_' . $lang},
        'date' => $model->date,
      ];
    }

    return [];
  }
}
