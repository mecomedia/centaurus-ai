<?php

namespace Mecomedia\CentaurusAI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string ping()
 */
class CentaurusAI extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'centaurus-ai'; // Alias aus dem Service Provider
  }
}