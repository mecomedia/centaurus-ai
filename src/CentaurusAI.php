<?php

namespace Mecomedia\CentaurusAI;

use Gemini\Enums\ModelType;
use Gemini\Laravel\Facades\Gemini;
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class CentaurusAI
{
    public const OPENAI_MODEL_4O = "chatgpt-4o-latest";
    public const OPENAI_MODEL_4O_NANO = "gpt-4o-mini";
    public const OPENAI_MODEL_5O_NANO = "gpt-5-nano";
    public const OPENAI_MODEL_41 = "gpt-4.1-2025-04-14";
    public const OPENAI_MODEL_41_NANO = "gpt-4.1-nano";
    public const OPENAI_MODEL_50 = "gpt-5-nano";
    public const OPENAI_MODEL_O1 = "o1-preview-2024-09-12";
    public const OPENAI_MODEL_O1_MINI = "o1-mini-2024-09-12";
    public const CLAUDE_37 = "claude-3-7-sonnet-latest";
    public const GEMINI_25 = 'models/gemini-2.5-flash-lite';

    public function __construct(protected array $config = [])
    {
    }

    public static function client(): CentaurusAI
    {
        return new CentaurusAI();
    }


    /**
     *
     * @param string $filename
     * @return string|null
     */
    public function processOcrDocument(string $filename): string|null
    {
        try {
            // Lade die JSON-Datei mit den Anmeldeinformationen
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . storage_path('app/keys/' . config('google-api.document_ai_json')));

            // DocumentUnderstandingServiceClient mit benutzerdefinierten Optionen erstellen
            $client = new DocumentProcessorServiceClient([
                'apiEndpoint' => config('google-api.document_ai_endpoint')
            ]);

            // Lade die PDF-Datei
            $image = Storage::disk('local')->get('files/' . $filename);

            // Erstelle Raw Document
            $raw = new RawDocument();
            $raw->setContent($image);
            $raw->setMimeType('application/pdf');

            # Fully-qualified Processor Name
            $processor = $client->processorName(config('google-api.document_ai_project_id'),
                config('google-api.document_ai_location'),
                config('google-api.document_ai_processor_id'));

            // Erstelle Process
            $process = new ProcessRequest();
            $process->setName($processor);
            $process->setRawDocument($raw);

            // Sende die Anfrage und erhalte die Antwort
            $response = $client->processDocument($process);

            // Verarbeite die Antwort
            $document = $response->getDocument();

            return $document->getText();
        } catch (\Exception $exception) {
            Log::error('Get OCR: ' . $exception->getMessage());
            return null;
        }
    }

    /**
     * Send data to Anthropic API
     */
    public function sendAnthropic($messages, $maxToken = 4096, $model = 'claude-3-5-sonnet-20240620'): null|string
    {
        try {
            $yourApiKey = config('anthropic.api_key');
            $client = \Anthropic::client($yourApiKey);

            $result = $client->messages()->create([
                'model' => $model,
                'max_tokens' => $maxToken,
                'messages' => $messages
            ]);

            $content = $this->convertJson($result->content[0]->text ?? null);

            if (!is_null($content) && !str_contains($content, 'json_result_error')) {
                return $content;
            } else {
                Log::error('Evaluate anthropic data: ' . $content);
                return null;
            }
        } catch (\Exception $exception) {
            Log::error('Evaluate anthropic data: ' . $exception->getMessage());
            return null;
        }
    }

    /**
     * Send data to OpenAI API
     */
    public function sendOpenAi($messages, $model): null|string
    {
        try {
            $request = [
                'model' => $model,
                'messages' => $messages,
                #                'temperature' => 0.3
            ];
            $result = OpenAI::chat()->create($request);
            $content = self::convertJson($result->choices[0]->message->content ?? null);

            if (!is_null($content) && !str_contains($content, 'json_result_error')) {
                return $content;
            } else {
                Log::error('Evaluate openai data: ' . $content);
                return null;
            }
        } catch (\Exception $exception) {
            Log::error('Evaluate openai data: ' . $exception->getMessage());
            return null;
        }
    }

    /**
     * Send data to OpenAI API
     */
    public function sendGemini($messages, $model = ModelType::GEMINI_FLASH): null|string
    {
        try {
            $chatMessages = "";
            foreach ($messages as $message) {
                $chatMessages .= $message['content'] . PHP_EOL;
            }

            $response = Gemini::generativeModel(model: $model)->generateContent($chatMessages);
            $content = self::convertJson($response->text() ?? null);

            if (!is_null($content) && !str_contains($content, 'json_result_error')) {
                return $content;
            } else {
                Log::error('Evaluate gemini data: ' . $content);
                return null;
            }
        } catch (\Exception $exception) {
            Log::error('Evaluate gemini data: ' . $exception->getMessage());
            return null;
        }
    }

    /**
     * Send data to OpenAI API
     */
    public function sendMistral($messages, $model = "mistral-large-latest", $temperature = 0.5): null|string
    {
        try {
            $request = [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature
            ];

            $curl = curl_init();
            $options = [
                CURLOPT_URL => config('mistral.api_url') . '/chat/completions',
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer ' . config('mistral.api_key'),
                ],
                CURLOPT_POSTFIELDS => json_encode($request),
            ];
            curl_setopt_array($curl, $options);
            $curl_response = curl_exec($curl);
            $result = $curl_response ? json_decode($curl_response) : null;

            $content = self::convertJson($result->choices[0]->message->content ?? null);

            if (!is_null($content) && !str_contains($content, 'json_result_error')) {
                return $content;
            } else {
                Log::error('Evaluate mistral data: ' . print_r($curl_response, true));
                return null;
            }
        } catch (\Exception $exception) {
            Log::error('Evaluate mistral data: ' . $exception->getMessage());
            return null;
        }
    }

    /**
     * Send data to OpenAI API
     */
    public function sendIonos($messages, $model = "meta-llama/Llama-3.3-70B-Instruct", $temperature = 0.3): null|string
    {
        try {
            $request = [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature
            ];

            $curl = curl_init();
            $options = [
                CURLOPT_URL => config('ionos.api_url') . '/v1/chat/completions',
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer ' . config('ionos.api_key'),
                ],
                CURLOPT_POSTFIELDS => json_encode($request),
            ];
            curl_setopt_array($curl, $options);
            $curl_response = curl_exec($curl);

            $result = $curl_response ? json_decode($curl_response) : null;

            $content = self::convertJson($result->choices[0]->message->content ?? null);

            if (!is_null($content) && !str_contains($content, 'json_result_error')) {
                return $content;
            } else {
                Log::error('Evaluate IONOS/Llama data: ' . print_r($curl_response, true));
                return null;
            }
        } catch (\Exception $exception) {
            Log::error('Evaluate IONOS/Llama data: ' . $exception->getMessage());
            return null;
        }
    }

    /**
     * Analyze OCR data by Anthropic API
     */
    protected function convertJson($text): string
    {
        $startpos = strpos($text, '```json');
        if ($startpos !== false) {
            $text = substr($text, $startpos + 7);
            $endpos = strpos($text, '```');
            if ($endpos !== false) {
                $notes = trim(substr($text, $endpos + 3, strlen($text) - $endpos - 3));
                $text = substr($text, 0, $endpos);
                if (strlen($notes) > 10) {
                    Log::warning('OCR to JSON notes: ' . $notes);
                }
            }
            $text = str_replace('```json', '', $text);
            $text = str_replace('```', '', $text);
        }
        $text = str_replace('```text', '', $text);
        $text = str_replace('```', '', $text);

        return $text;
    }

    /**
     * Send data to OpenAI API
     */
    public function getEmbeddedFromOpenAi($text, $model = "text-embedding-3-large", $format = "float"): null|array
    {
        $request = [
            "input" => $text,
            "model" => $model,
            "encoding_format" => $format
        ];
        try {
            $openai = OpenAI::embeddings()->create($request)->toArray();
        } catch (\Exception $exception) {
            Log::error('Get embedding from OpenAI: ' . $exception->getMessage());
            return null;
        }

        return $openai['data'][0]['embedding'] ?? null;
    }

}