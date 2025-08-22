<?php

namespace Mecomedia\CentaurusAITests;

use Orchestra\Testbench\TestCase;
use Mecomedia\CentaurusAI\CentaurusAIServiceProvider;
use Mecomedia\CentaurusAI\Facades\CentaurusAI;

class ExampleTest extends TestCase
{
  protected function getPackageProviders($app)
  {
    return [CentaurusAIServiceProvider::class];
  }

  protected function getPackageAliases($app)
  {
    return ['CentaurusAI' => CentaurusAI::class];
  }
}