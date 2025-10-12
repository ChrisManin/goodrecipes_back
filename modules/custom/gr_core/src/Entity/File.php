<?php

namespace Drupal\gr_core\Entity;

use Drupal\file\Entity\File as EntityFile;

class File extends EntityFile
{
  /**
   * Construct webp image style url
   */
  public function buildImageStyleUri($visual_style = '{style_name}')
  {
    // TODO : Mettre en place les styles d'image
    $file_url_generator = \Drupal::service('file_url_generator');
    
    $uri = parse_url($this->uri->value);
    $scheme_path = $file_url_generator->generateString($uri['scheme'] . '://');
    $buildUri = str_replace($scheme_path, $scheme_path . 'styles/' . $visual_style . '/' . $uri['scheme'] . '/', $file_url_generator->generateAbsoluteString($this->uri->value));
    $buildUri .= '.webp';

    return $buildUri;
  }

  /**
   * Construct webp image style url
   */
  public function buildImageStyleUriForPlatform($visual_style = '{style_name}')
  {
    $file_url_generator = \Drupal::service('file_url_generator');
    
    $uri = parse_url($this->uri->value);
    $scheme_path = $file_url_generator->generateString($uri['scheme'] . '://');
    $buildUri = str_replace('{style_name}', '320x240', str_replace($scheme_path, $scheme_path . 'styles/' . $visual_style . '/' . $uri['scheme'] . '/', $file_url_generator->generateAbsoluteString($this->uri->value)));
    return $buildUri;
  }
}