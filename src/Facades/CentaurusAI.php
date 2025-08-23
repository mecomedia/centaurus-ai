<?php

namespace Mecomedia\CentaurusAI\Facades;

use Gemini\Enums\ModelType;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mecomedia\CentaurusAI\CentaurusAI client()
 * @method static string|null processOcrDocument(string $filename)
 * @method static string|null sendAnthropic($messages, $maxToken = 4096, $model = 'claude-3-5-sonnet-20240620')
 * @method static string|null sendOpenAi($messages, $model)
 * @method static string|null sendGemini($messages, $model = ModelType::GEMINI_FLASH)
 * @method static string|null sendMistral($messages, $model = "mistral-large-latest", $temperature = 0.5)
 * @method static string|null sendIonos($messages, $model = "meta-llama/Llama-3.3-70B-Instruct", $temperature = 0.3)
 * @method static array|null getEmbeddedFromOpenAi($text, $model = "text-embedding-3-large", $format = "float")
 *
 * @see \Mecomedia\CentaurusAI\CentaurusAI
 */
class CentaurusAI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'centaurus-ai'; // Alias aus dem Service Provider
    }
}