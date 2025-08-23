# Centaurus-AI for Laravel

### Introduction

CentaurusAI is a library that combines the chatbot API of the most important LLMs so that LLMs can be quickly and easily exchanged to another. Access is standardized to reduce implementation effort.

Access to the Google Document API is also implemented for the ocr evaluation of PDF documents. This makes it easy to extract text from uploaded documents.

The Embedding API from OpenAI is also implemented for use in environments with vector databases.

### Installation

Installation via composer

```shell
composer require mecomedia/centaurus-ai
```

Configuration of the AI API credentials in the .env file

```dotenv
CLAUDE3_API_KEY=sk-ant-api03-XXXXXXXXX

OPENAI_API_KEY=sk-proj-XXXXXXXXX
OPENAI_ORGANIZATION=org-XXXXXXXXX
OPENAI_REQUEST_TIMEOUT=300

GEMINI_API_KEY=XXXXXXXXX
GEMINI_REQUEST_TIMEOUT=300

MISTRAL_API_KEY=XXXXXXXXX
MISTRAL_API_URL=https://api.mistral.ai/v1

IONOS_API_KEY=XXXXXXXXX
IONOS_API_URL=https://openai.inference.de-txl.ionos.com

GOOGLE_DOCUMENT_AI_JSON=core-forklift-XXXXXXXXX.json
GOOGLE_DOCUMENT_AI_PROJECT_ID=XXXXXXXXX
GOOGLE_DOCUMENT_AI_PROCESSOR_ID=XXXXXXXXX
GOOGLE_DOCUMENT_AI_LOCATION=eu
GOOGLE_DOCUMENT_AI_ENDPOINT=eu-documentai.googleapis.com
```

Use the facade in your controllers or services

```php
use Mecomedia\CentaurusAI\Facades\CentaurusAI;
```

### Process OCR (PDF documents only)

```php
$ocr = CentaurusAI::processOcrDocument($filename);
```

### AI chat requests to several AI API

```php
$messages = [
    [
        'role' => 'user',
        'content' => 'Hello, how are you?'
    ],
    [
        'role' => 'user',
        'content' => 'I am fine, thank you.'
    ]
];

$response = sendAnthropic($messages, 4096, AIModels::CLAUDE_37);
$response = sendOpenAI($messages, AIModels::OPENAI_MODEL_5O_NANO);
$response = sendGemini($messages, AIModels::GEMINI_25_LITE, 0.5);
$response = sendMistral($messages, AIModels::MISTRAL_LARGE, 0.3);
$response = sendIonos($messages, AIModels::IONOS_LAMA_33, 'float');
```
