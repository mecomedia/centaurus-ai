<?php

namespace Mecomedia\CentaurusAITests\Integration;

use Dotenv\Dotenv;
use Illuminate\Support\Facades\Storage;
use Mecomedia\CentaurusAI\CentaurusAI;
use Mecomedia\CentaurusAI\CentaurusAIServiceProvider;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Group;

class MistralOcrIntegrationTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            CentaurusAIServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $envPath = dirname(__DIR__, 2);

        if (file_exists($envPath . '/.env')) {
            Dotenv::createImmutable($envPath)->safeLoad();
        }

        $apiKey = $_ENV['MISTRAL_API_KEY']
            ?? getenv('MISTRAL_API_KEY')
            ?? env('MISTRAL_API_KEY');

        $apiUrl = $_ENV['MISTRAL_API_URL']
            ?? getenv('MISTRAL_API_URL')
            ?? env('MISTRAL_API_URL')
            ?? 'https://api.mistral.ai/v1';

        $app['config']->set('mistral.api_key', $apiKey);
        $app['config']->set('mistral.api_url', rtrim($apiUrl, '/'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (empty(config('mistral.api_key'))) {
            $this->markTestSkipped('MISTRAL_API_KEY ist nicht gesetzt. Integration Test wird übersprungen.');
        }

        $fixturePath = __DIR__ . '/../Fixtures/mistral-ocr-test.pdf';

        if (!file_exists($fixturePath)) {
            $this->markTestSkipped('Fixture fehlt: tests/Fixtures/mistral-ocr-test.pdf');
        }

        Storage::disk('local')->put(
            'files/mistral-ocr-test.pdf',
            file_get_contents($fixturePath)
        );
    }

    #[Group('integration')]
    #[Group('mistral')]
    public function test_send_mistral_ocr_calls_real_api_and_returns_pages(): void
    {
        $result = (new CentaurusAI())->sendMistralOcr('mistral-ocr-test.pdf');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('pages', $result);
        $this->assertNotEmpty($result['pages']);

        $firstPage = $result['pages'][0];

        $this->assertIsArray($firstPage);
        $this->assertTrue(
            array_key_exists('markdown', $firstPage)
            || array_key_exists('text', $firstPage)
            || array_key_exists('html', $firstPage),
            'Die erste OCR-Seite enthält weder markdown, text noch html.'
        );
    }

    #[Group('integration')]
    #[Group('mistral')]
    public function test_send_mistral_ocr_detects_expected_text_from_fixture(): void
    {
        $result = (new CentaurusAI())->sendMistralOcr('mistral-ocr-test.pdf');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('pages', $result);

        $recognizedText = json_encode($result, JSON_UNESCAPED_UNICODE);

        print_r($recognizedText);

        $this->assertIsString($recognizedText);
        $this->assertStringContainsStringIgnoringCase('Centaurus', $recognizedText);
        $this->assertStringContainsStringIgnoringCase('Mistral', $recognizedText);
        $this->assertStringContainsStringIgnoringCase('TEST-123', $recognizedText);
    }
}