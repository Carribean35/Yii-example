<?php

namespace common\components;

use Exception;
use yii\imagine\Image;

class Images
{
  public static function uploadImage($inst, $path, $filename = false, $sizes = [])
  {
    if (empty($inst)) {
      return false;
    }
    try {
      if (!file_exists($path)) {
        mkdir($path, 0777, true);
      }
      if ($filename) {
        $filename = mb_strtolower($filename);
      } else {
        $filename = mb_strtolower($inst->getBaseName());
      }
      $filename .= '.' . $inst->getExtension();
      $filename = preg_replace('/[^ a-zа-яё\d\.-]/ui', '', $filename);
      $filename = str_replace(' ', '-', $filename);
      if (empty($sizes)) {
        $inst->saveAs($path . $filename);
      } else {
        foreach ($sizes as $fld => $size) {
          if (!empty($fld)) {
            $fld .= '/';
          }
          if (!file_exists($path . $fld)) {
            mkdir($path . $fld, 0777, true);
          }
          if (!empty($size['width']) && !empty($size['height'])) {
            Image::thumbnail($inst->tempName, $size['width'], $size['height'])
              ->save($path . $fld . $filename, ['quality' => $size['quality']]);
          }
        }
      }
      return $filename;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function deleteImage($path, $filename, $sizes = [])
  {
    foreach ($sizes as $fld => $size) {
      if (!empty($fld)) {
        $fld .= '/';
      }
      if (is_file($path . $fld . $filename)) {
        unlink($path . $fld . $filename);
      }
    }
    if (is_file($path . $filename)) {
      unlink($path . $filename);
    }
  }

  public static function getImage($path, $url, $filename, $size = false)
  {
    if (empty($filename)) {
      return false;
    }
    if (!empty($size)) {
      $size .= '/';
    }
    if (is_file($path . $size . $filename)) {
      return $url . $size . $filename;
    }
    return false;
  }
}
