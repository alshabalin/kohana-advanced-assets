<?php

/**
 * Controller handles static routes to assets
 *
 * @package   Advanced_Assets
 * @author    Alexei Shabalin <mail@alshabalin.com>
 */
class Controller_Advanced_Assets extends Controller {

  public function action_sendfile()
  {
    $file   = $this->request->param('file');
    $format = $this->request->param('format');

    $file   = preg_replace('/\-[0-9a-f]{1,32}$/', '', $file);

    $file_name = Assets::get_file($file, $format);

    if ($file_name && is_file($file_name))
    {
      $this->response->send_file($file_name, NULL, ['inline' => true]);
    }
    else
    {
      throw new HTTP_Exception_404;
    }

  }

}
