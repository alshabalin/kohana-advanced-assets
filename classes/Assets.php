<?php

/**
 * Class Assets finds static resources and provides access to them
 *
 * @author  Alexei Shabalin <mail@alshabalin.com>
 */
class Assets {

  public static function stylesheet_link_tag($source, array $attributes = NULL)
  {
    if (strpos('//', $source) === FALSE && isset($source[0]) && $source[0] !== '/')
    {
      $version = '';

      if (Kohana::$config->load('assets.versionizable') === TRUE)
      {
        $file_name = Assets::get_file($source, 'css');

        if ($file_name && is_file($file_name))
        {
          $version = '-' . hash_hmac_file('md5', $file_name, Kohana::$config->load('assets.versionizable.hmac_password'));
        }
      }

      $source = '/assets/' . $source . $version . '.css';
    }

    return HTML::style($source, $attributes);
  }


  public static function javascript_include_tag($source, array $attributes = NULL)
  {
    if (strpos('//', $source) === FALSE && isset($source[0]) && $source[0] !== '/')
    {
      $version = '';

      if (Kohana::$config->load('assets.versionizable') === TRUE)
      {
        $file_name = Assets::get_file($source, 'js');

        if ($file_name && is_file($file_name))
        {
          $version = '-' . hash_hmac_file('md5', $file_name, Kohana::$config->load('assets.versionizable.hmac_password'));
        }
      }

      $source = '/assets/' . $source . $version . '.js';
    }

    return HTML::script($source, $attributes);
  }

  public static function image_tag($source, array $attributes = NULL)
  {
    if (strpos('//', $source) === FALSE && isset($source[0]) && $source[0] !== '/')
    {
      $version = '';

      $format = 'png';

      if (preg_match('#^(?<source>.+)\.(?<format>\w+)$#', $source, $matches))
      {
        $source = $matches['source'];
        $format = $matches['format'];
      }

      if (Kohana::$config->load('assets.versionizable') === TRUE)
      {
        $file_name = Assets::get_file($source, $format);

        if ($file_name && is_file($file_name))
        {
          $version = '-' . hash_hmac_file('md5', $file_name, Kohana::$config->load('assets.versionizable.hmac_password'));
        }
      }

      $source = '/assets/' . $source . $version . '.' . $format;
    }

    return HTML::image($source, $attributes);
  }

  public static function video_tag($source, array $attributes = NULL)
  {
    if (strpos('//', $source) === FALSE && isset($source[0]) && $source[0] !== '/')
    {
      $version = '';

      $format = 'mp4';

      if (preg_match('#^(?<source>.+)\.(?<format>\w+)$#', $source, $matches))
      {
        $source = $matches['source'];
        $format = $matches['format'];
      }

      if (Kohana::$config->load('assets.versionizable') === TRUE)
      {
        $file_name = Assets::get_file($source, $format);

        if ($file_name && is_file($file_name))
        {
          $version = '-' . hash_hmac_file('md5', $file_name, Kohana::$config->load('assets.versionizable.hmac_password'));
        }
      }

      $source = '/assets/' . $source . $version . '.' . $format;

      $mime = File::mime_by_ext($format);
    }
    else
    {
      $mime = File::mime($source);
    }

    return '<video'.HTML::attributes($attributes).'><source src="' . $source . '" type="' . $mime . '" /><a href="' . $source . '">' . $source . '</a></video>';
  }

  public static function get_file($file, $format)
  {
    $config = Kohana::$config->load('assets');
    $paths  = $config['paths'];

    switch ($format)
    {
      case 'js':
      case 'js.map':
        $file_name = Kohana::find_file('assets', 'javascripts/' . $file, $format);
        if ($file_name === FALSE)
        {
          if (isset($paths['javascripts'][$file]))
          {
            $file_name = $paths['javascripts'][$file];
          }
        }
        break;

      case 'css':
      case 'css.map':
        $file_name = Kohana::find_file('assets', 'stylesheets/' . $file, $format);
        if ($file_name === FALSE)
        {
          if (isset($paths['stylesheets'][$file]))
          {
            $file_name = $paths['stylesheets'][$file];
          }
        }
        break;

      case 'jpg':
      case 'jpeg':
      case 'jpe':
      case 'png':
      case 'gif':
      case 'svg':
      case 'svgz':
        $file_name = Kohana::find_file('assets', 'images/' . $file, $format);
        if ($file_name === FALSE)
        {
          if (isset($paths['images'][$file . $format]))
          {
            $file_name = $paths['images'][$file . $format];
          }
        }
        break;

      case 'swf':
        $file_name = Kohana::find_file('assets', 'swf/' . $file, $format);
        break;

      case 'mp3':
      case 'ogg':
        $file_name = Kohana::find_file('assets', 'sounds/' . $file, $format);
        break;

      case 'mp4':
      case 'flv':
        $file_name = Kohana::find_file('assets', 'videos/' . $file, $format);
        break;

      case 'otf':
      case 'ttf':
      case 'woff':
      case 'woff2':
      case 'eot':
        $file_name = Kohana::find_file('assets', 'fonts/' . $file, $format);
        if ($file_name === FALSE)
        {
          if (isset($paths['fonts'][$file]))
          {
            $file_name = $paths['fonts'][$file] . $format;
          }
        }
        break;
    }

    return $file_name;
  }

}
