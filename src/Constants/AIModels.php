<?php

namespace Mecomedia\CentaurusAI\Constants;

/**
 * Zentrale Konstanten für AI-Modelle
 */
class AIModels
{
    /* OpenAI Models */
    public const OPENAI_MODEL_55 = "gpt-5.5";
    public const OPENAI_MODEL_54 = "gpt-5.4";
    public const OPENAI_MODEL_54_MINI = "gpt-5.4-mini";
    public const OPENAI_MODEL_54_NANO = "gpt-5.4-nano";
    public const OPENAI_MODEL_51 = "gpt-5.1";
    public const OPENAI_MODEL_50 = "gpt-5";
    public const OPENAI_MODEL_50_MINI = "gpt-5-mini";
    public const OPENAI_MODEL_50_NANO = "gpt-5-nano";
    public const OPENAI_MODEL_41 = "gpt-4.1";
    public const OPENAI_MODEL_41_NANO = "gpt-4.1-nano";
    public const OPENAI_MODEL_4O = "chatgpt-4o-latest";
    public const OPENAI_MODEL_4O_MINI = "gpt-4o-mini";
    public const OPENAI_MODEL_O1 = "o1";
    public const OPENAI_MODEL_O1_MINI = "o1-mini-2024-09-12";
    public const OPENAI_MODEL_O3 = "o3";
    public const OPENAI_MODEL_O3_MINI = "o3-mini";
    public const OPENAI_EMBEDDING_3_LARGE = "text-embedding-3-large";

    /* Claude Models */
    public const CLAUDE_OPUS_48 = "claude-opus-4-8";
    public const CLAUDE_SONNET_46 = "claude-sonnet-4-6";
    public const CLAUDE_HAIKU_45 = "claude-haiku-4-5-20251001";
    public const CLAUDE_OPUS_45 = "claude-opus-4-5-20251101";
    public const CLAUDE_OPUS_41 = "claude-opus-4-1-20250805";
    public const CLAUDE_OPUS_4 = "claude-opus-4-20250514";
    public const CLAUDE_SONNET_45 = "claude-sonnet-4-5-20250929";
    public const CLAUDE_SONNET_4 = "claude-sonnet-4-20250514";
    public const CLAUDE_SONNET_37 = "claude-3-7-sonnet-latest";

    /* Gemini Models */
    public const GEMINI_35_FLASH = 'models/gemini-3.5-flash';
    public const GEMINI_31_FLASH_LITE = 'models/gemini-3.1-flash-lite';
    public const GEMINI_31_PRO = 'models/gemini-3.1-pro-preview';
    public const GEMINI_3_PRO = 'models/gemini-3-pro-preview';
    public const GEMINI_3_FLASH = 'models/gemini-3-flash-preview';
    public const GEMINI_25_PRO = 'models/gemini-2.5-pro';
    public const GEMINI_25_FLASH = 'models/gemini-2.5-flash';
    public const GEMINI_25_LITE = 'models/gemini-2.5-flash-lite';
    public const GEMINI_2_FLASH = 'models/gemini-2.0-flash';

    /* Mistral Models */
    public const MISTRAL_LARGE = 'mistral-large-latest';
    public const MISTRAL_MEDIUM = 'mistral-medium-latest';
    public const MISTRAL_SMALL = 'mistral-small-latest';
    public const MISTRAL_OCR = 'mistral-ocr-latest';

    /* IONOS Models */
    public const IONOS_LAMA_33 = "meta-llama/Llama-3.3-70B-Instruct";
    public const IONOS_OPENAI_OSS_120b = "openai/gpt-oss-120b";
}
