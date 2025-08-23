<?php

namespace Mecomedia\CentaurusAI\Facades;

use Illuminate\Support\Facades\Facade;
use Mecomedia\CentaurusAI\Constants\AIModels;

/**
 * @method static string|null processOcrDocument(string $filename)
 * @method static string|null sendAnthropic($messages, $maxToken = 4096, $model = AIModels::CLAUDE_37)
 * @method static string|null sendOpenAi($messages, $model = AIModels::OPENAI_MODEL_5O_NANO)
 * @method static string|null sendGemini($messages, $model = AIModels::GEMINI_25_LITE)
 * @method static string|null sendMistral($messages, $model = AIModels::MISTRAL_LARGE, $temperature = 0.5)
 * @method static string|null sendIonos($messages, $model = AIModels::IONOS_LAMA_33, $temperature = 0.3)
 * @method static array|null getEmbeddedFromOpenAi($text, $model = AIModels::OPENAI_EMBEDDING_3_LARGE, $format = "float")
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