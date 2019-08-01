<?php
  $path = dirname(__FILE__).'';
  if ($handle = opendir($path)) {

    while (false !== ($file = readdir($handle))) {
        if ((time()-filectime($path.'/'.$file)) < 86400) {  // 86400 = 60*60*24
          if (preg_match('/\.log$/i', $file)) {
            unlink($path.'/'.$file);
          }
        }
    }
  }
?>