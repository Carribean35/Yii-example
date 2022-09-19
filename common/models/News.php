<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\base\EActiveRecord;
use common\components\Images;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property string $text
 * @property bool $visible
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $url
 * @property string $date
 */
class News extends EActiveRecord
{

  public $image_input;
  public static $image_path = '/data/news/main/';
  public static $image_extensions = ['png', 'jpg', 'jpeg'];
  public static $image_sizes = ['' => ['width' => 700, 'height' => 400, 'quality' => 90]];

  public static $text_image_path = '/data/news/gallery/';
  public static $text_image_extensions = ['png', 'jpg', 'jpeg'];
  public static $text_image_sizes = ['' => ['width' => 1000, 'height' => 600, 'quality' => 90]];

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'news';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [[
        'name_ru', 'text_ru', 'title_ru', 'keywords_ru', 'description_ru', 'short_name_ru', 'short_text_ru',
        'name_us', 'text_us', 'title_us', 'keywords_us', 'description_us', 'short_name_us', 'short_text_us',
        'name_tr', 'text_tr', 'title_tr', 'keywords_tr', 'description_tr', 'short_name_tr', 'short_text_tr', 'image'
      ], 'filter', 'filter' => 'trim'],
      ['visible', 'filter', 'filter' => 'boolval'],

      [['short_text_ru', 'url', 'name_ru', 'short_name_ru', 'date'], 'required'],
      [['text_ru', 'text_us', 'text_tr'], 'string'],
      [['visible'], 'boolean'],
      [['name_ru', 'name_us', 'name_tr', 'url'], 'string', 'max' => 100],
      [['short_name_ru', 'short_name_us', 'short_name_tr'], 'string', 'max' => 90],
      [['short_text_ru', 'short_text_us', 'short_text_tr'], 'string', 'max' => 250],
      [['title_ru', 'title_us', 'title_tr'], 'string', 'max' => 70],
      [['keywords_ru', 'description_us', 'keywords_tr', 'description_ru', 'keywords_us', 'description_tr'], 'string', 'max' => 200],
      ['date', 'datetime', 'format' => 'php:d.m.Y', 'message' => 'Неправильный формат даты'],
      [['url'], 'unique'],
      [['image_input'], 'file', 'extensions' => static::$image_extensions],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'name_ru' => 'Название на русском',
      'name_us' => 'Название на английском',
      'name_tr' => 'Название на турецком',
      'short_name_ru' => 'Короткое название на русском',
      'short_name_us' => 'Короткое название на английском',
      'short_name_tr' => 'Короткое название на турецком',
      'text_ru' => 'Текст на русском',
      'text_us' => 'Текст на английском',
      'text_tr' => 'Текст на турецком',
      'short_text_ru' => 'Короткое описание на русском',
      'short_text_us' => 'Короткое описание на английском',
      'short_text_tr' => 'Короткое описание на турецком',
      'visible' => 'Видимость',
      'title_ru' => 'Title на русском',
      'title_us' => 'Title на английском',
      'title_tr' => 'Title на турецком',
      'keywords_ru' => 'Keywords на русском',
      'keywords_us' => 'Keywords на английском',
      'keywords_tr' => 'Keywords на турецком',
      'description_ru' => 'Description на русском',
      'description_us' => 'Description на английском',
      'description_tr' => 'Description на турецком',
      'url' => 'Url',
      'date' => 'Дата',
      'image_input' => 'Изображение',
    ];
  }

  public function afterFind()
  {
    $tmp = \DateTime::createFromFormat('Y-m-d', $this->date);
    if ($tmp) {
      $this->date = $tmp->format('d.m.Y');
      $this->setOldAttribute('date', $this->date);
    }
  }

  public function beforeSave($insert)
  {
    if (!parent::beforeSave($insert)) {
      return false;
    }

    $tmp = \DateTime::createFromFormat('d.m.Y', $this->date);
    if ($tmp) {
      $this->date = $tmp->format('Y-m-d');
    }

    $params = ['name', 'text', 'title', 'keywords', 'description', 'short_name', 'short_text'];
    foreach ($params as $v) {
      if (empty($this->{$v . '_us'})) {
        $this->{$v . '_us'} = $this->{$v . '_ru'};
      }
      if (empty($this->{$v . '_tr'})) {
        $this->{$v . '_tr'} = $this->{$v . '_ru'};
      }
    }

    return true;
  }

  public function search($params)
  {
    $query = $this->find();

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 50,
      ],
      'sort' => ['defaultOrder' => ['date' => SORT_DESC]]
    ]);

    $this->load($params);

    return $dataProvider;
  }

  public static function getImagePath()
  {
    return Yii::getAlias('@common' . static::$image_path);
  }
  public static function getImageUrl()
  {
    return static::$image_path;
  }
  public static function getTextImagePath()
  {
    return Yii::getAlias('@common' . static::$text_image_path);
  }
  public static function getTextImageUrl()
  {
    return static::$text_image_path;
  }

  public function delete()
  {
    Images::deleteImage(static::getImagePath(), $this->image);
    parent::delete();
  }
}
