<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\models\News;
use common\components\Images;

class NewsController extends BController
{

  public function actionIndex()
  {
    $model = new News();
    $dataProvider = $model->search(Yii::$app->request->queryParams);

    return $this->render('index', [
      'model' => $model,
      'dataProvider' => $dataProvider,
    ]);
  }

  public function actionValidate()
  {
    if (Yii::$app->request->isAjax) {
      Yii::$app->response->format = Response::FORMAT_JSON;
      $model = News::findOne(intval(Yii::$app->request->post('News')['id']));
      if (empty($model)) {
        $model = new News();
      }
      $model->load(Yii::$app->request->post());

      return ActiveForm::validate($model);
    }
    \Yii::$app->end();
  }

  public function actionItem($id = false)
  {
    if ($id !== false) {
      $model = News::findOne($id);
    } else {
      $model = new News();
    }

    if (Yii::$app->request->isPost) {
      $model->load(Yii::$app->request->post());
      $model->save();
      $model->afterFind();

      $filename = Images::uploadImage(UploadedFile::getInstance($model, 'image_input'), News::getImagePath(), $model->id, News::$image_sizes);
      if (!empty($filename)) {
        Images::deleteImage(News::getImagePath(), $model->image);
        $model->image = $filename;
        $model->save();
      }
      Yii::$app->cache->flush();

      return $this->redirect(['index']);
    }

    return $this->render('item', array('model' => $model));
  }

  public function actionDelete($id)
  {
    $model = News::findOne($id);
    $model->delete();
    Yii::$app->cache->flush();

    return $this->redirect(['index']);
  }

  public function actionImageUpload()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    if (Yii::$app->request->isAjax) {
      $inst = UploadedFile::getInstanceByName('file');
      if (!in_array($inst->getExtension(), News::$text_image_extensions)) {
        return ['error' => 'Error extension'];
      }
      $filename = Images::uploadImage($inst, News::getTextImagePath(), false, News::$text_image_sizes);
      return ['id' => $filename, 'filelink' => News::getTextImageUrl() . $filename, 'filename' => $filename];
    }
    return ['error' => 'Error image upload'];
  }

  public function actionImagesGet()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $files = [];

    foreach (FileHelper::findFiles(Yii::getAlias(News::getTextImagePath()), ['only' => array_map(function ($data) {
      return '*.' . $data;
    }, News::$text_image_extensions)]) as $path) {
      $file = basename($path);
      $url = News::getTextImageUrl() . urlencode($file);

      $files[] = [
        'id' => $file,
        'title' => $file,
        'thumb' => $url,
        'image' => $url,
      ];
    }

    return $files;
  }
}
