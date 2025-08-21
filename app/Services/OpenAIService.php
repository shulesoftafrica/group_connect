<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OpenAIService
{
    private $apiKey;
    private $model;
    
    public function __construct()
    {
        $this->apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        $this->model = config('ai.model', 'gpt-4o-mini');
        
        if (!$this->apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }
    }
    
    /**
     * Make OpenAI chat completion request
     * 
     * @param string $prompt The prompt/message content
     * @param array $config Optional configuration overrides
     * @return string AI response content
     */
    public function chatCompletion(string $prompt, array $config = []): string
    {
        $defaultConfig = [
            'model' => $this->model,
            'temperature' => 0.3,
            'max_tokens' => 2000
        ];
        
        $config = array_merge($defaultConfig, $config);
        
        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];
        
        return $this->makeRequest($messages, $config);
    }
    
    /**
     * Make OpenAI chat completion request with system prompt
     * 
     * @param string $systemPrompt System instructions
     * @param string $userPrompt User message
     * @param array $config Optional configuration overrides
     * @return string AI response content
     */
    public function chatCompletionWithSystem(string $systemPrompt, string $userPrompt, array $config = []): string
    {
        $defaultConfig = [
            'model' => $this->model,
            'temperature' => 0.3,
            'max_tokens' => 2000
        ];
        
        $config = array_merge($defaultConfig, $config);
        
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt]
        ];
        
        return $this->makeRequest($messages, $config);
    }
    
    /**
     * Make the actual HTTP request to OpenAI API
     */
    private function makeRequest(array $messages, array $config): string
    {
        try {
            // Configure HTTP client based on environment
            if (app()->environment('local')) {
                $httpClient = Http::withOptions([
                    'verify' => 'C:\xampp\php\certs\cacert.pem', // point to your file
                ]);
            } else {
                $httpClient = Http::withOptions([
                    'verify' => true, // Use default verification for live environment
                ]);
            }
            
            $response = $httpClient->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $config['model'],
                'messages' => $messages,
                'temperature' => $config['temperature'],
                'max_tokens' => $config['max_tokens']
            ]);

            Log::info('OpenAI Request', [
                'model' => $config['model'],
                'message_count' => count($messages),
                'temperature' => $config['temperature']
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('OpenAI Response Success', [
                    'usage' => $data['usage'] ?? null,
                    'finish_reason' => $data['choices'][0]['finish_reason'] ?? null
                ]);
                
                return $data['choices'][0]['message']['content'] ?? '';
            } else {
                throw new \Exception('OpenAI API error: ' . $response->status() . ' - ' . $response->body());
            }
            
        } catch (\Exception $e) {
            Log::error('OpenAI Request Error', [
                'error' => $e->getMessage(),
                'config' => $config
            ]);
            throw $e;
        }
    }
}
