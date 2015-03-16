<?php

Route::set(
  'assets',
  'assets/<file>.<format>',
  [
    'file' => '[\w\-\./]+',
    'format' => '(js|js\.map|css|css\.map|jpe?g|jpe|png|gif|swf|svgz?|mp3|ogg|mp4|flv|otf|ttf|woff2?|eot)'
  ]
)
->defaults([
  'controller' => 'Advanced_Assets',
  'action'     => 'sendfile',
]);
