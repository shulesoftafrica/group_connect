<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DataSummarizerService
{
    private $openAIService;
    
    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }
    
    /**
     * Summarize database results into user-friendly JSON response
     * 
     * @param string $userQuestion Original user question
     * @param mixed $queryResult Raw database query results
     * @return array Structured JSON response
     */
    public function summarizeData(string $userQuestion, $queryResult): array
    {
        try {
            // Build conversational prompt for AI
            $prompt = $this->buildSummaryPrompt($userQuestion, $queryResult);
            
            Log::info('Data Summarizer - User Question', ['question' => $userQuestion]);
            Log::info('Data Summarizer - Query Result', ['result' => $queryResult]);
            Log::info('Data Summarizer - Prompt', ['prompt' => $prompt]);
            
            // Call AI to generate user-friendly response
            $summaryResponse = $this->callAI($prompt);
            
            // Parse and validate the JSON response
            $structuredResponse = $this->parseJsonResponse($summaryResponse);
            
            Log::info('Data Summarizer - Final Response', ['response' => $structuredResponse]);
            
            return $structuredResponse;
            
        } catch (\Exception $e) {
            Log::error('Data Summarizer Error', [
                'question' => $userQuestion,
                'result' => $queryResult,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return fallback response
            return $this->getFallbackResponse($userQuestion, $queryResult);
        }
    }
    
    /**
     * Build conversational prompt for AI to create user-friendly response
     */
    private function buildSummaryPrompt(string $userQuestion, $queryResult): string
    {
        $resultData = $this->formatQueryResult($queryResult);
        
        return <<<PROMPT
You are ShuleSoft AI, an intelligent assistant for school management. Your task is to create a user-friendly response based on the database query results.

USER'S ORIGINAL QUESTION: {$userQuestion}

DATABASE QUERY RESULTS: {$resultData}

RESPONSE REQUIREMENTS:
- Act as a conversational assistant providing insights in plain English
- Never show technical details like SQL queries or raw data
- Create explanations that a non-technical school administrator can understand
- Be specific with numbers and provide context
- Include actionable recommendations when appropriate

RESPONSE FORMAT:
Return a JSON object with this exact structure:
{
    "type": "text|table|chart|kpi",
    "data": {
        "summary": "A clear, user-friendly summary in plain English explaining what the data shows",
        "details": "Detailed explanation in conversational language with specific insights",
        "charts": [
            {
                "title": "Chart Title",
                "type": "bar|line|pie|doughnut",
                "data": {
                    "labels": ["Label1", "Label2"],
                    "datasets": [{
                        "label": "Dataset Label",
                        "data": [123, 456],
                        "backgroundColor": ["#color1", "#color2"]
                    }]
                }
            }
        ],
        "tables": [
            {
                "title": "Table Title",
                "headers": ["Column1", "Column2"],
                "rows": [["Value1", "Value2"]],
                "summary": "Brief explanation of what the table shows"
            }
        ],
        "kpis": [
            {
                "title": "KPI Name",
                "value": "123",
                "formatted_value": "123K",
                "trend": "up|down|stable",
                "change": "+5.2%",
                "description": "What this KPI means"
            }
        ]
    },
    "recommendations": [
        "Clear actionable recommendation 1",
        "Clear actionable recommendation 2"
    ]
}

COMMUNICATION STYLE:
- Use clear, professional language
- Explain financial figures in context (e.g., "Total revenue this month is Tsh 450,000")
- Provide insights, not just numbers
- Be conversational but authoritative
- Focus on actionable insights

IMPORTANT:
- Base all content on the actual query results provided
- Do not invent numbers or data not present in the results
- If results are empty, explain that clearly
- Choose the appropriate type (text, table, chart, kpi) based on the data
- Return only valid JSON, no additional text
PROMPT;
    }
    
    /**
     * Format query result for AI processing
     */
    private function formatQueryResult($queryResult): string
    {
        if (empty($queryResult)) {
            return 'No data found';
        }
        
        if (is_array($queryResult)) {
            if (count($queryResult) === 1 && is_object($queryResult[0])) {
                // Single row result
                $row = (array) $queryResult[0];
                $formatted = [];
                foreach ($row as $key => $value) {
                    $formatted[] = "{$key}: {$value}";
                }
                return implode(', ', $formatted);
            } else {
                // Multiple rows result
                return json_encode($queryResult, JSON_PRETTY_PRINT);
            }
        }
        
        if (is_object($queryResult)) {
            return json_encode($queryResult, JSON_PRETTY_PRINT);
        }
        
        return (string) $queryResult;
    }
    
    /**
     * Call AI service to generate summary
     */
    private function callAI(string $prompt): string
    {
        try {
            $config = [
                'temperature' => 0.7, // Higher temperature for creative summarization
                'max_tokens' => 2000,  // Allow longer responses for detailed summaries
            ];
            
            $response = $this->openAIService->chatCompletion($prompt, $config);
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('AI Call Error in Data Summarizer', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Make actual AI request (placeholder - implement with your AI service)
     */
    private function makeAIRequest(string $prompt, array $config): string
    {
        // This method is now deprecated - using OpenAIService instead
        return $this->callAI($prompt);
    }
    
    /**
     * Parse and validate JSON response from AI
     */
    private function parseJsonResponse(string $response): array
    {
        // Clean the response
        $cleanResponse = trim($response);
        
        // Remove any markdown formatting
        $cleanResponse = preg_replace('/^```json?\s*/i', '', $cleanResponse);
        $cleanResponse = preg_replace('/```\s*$/', '', $cleanResponse);
        
        // Try to decode JSON
        $decoded = json_decode($cleanResponse, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON Parse Error', [
                'response' => $response,
                'cleaned' => $cleanResponse,
                'error' => json_last_error_msg()
            ]);
            
            throw new \Exception('Invalid JSON response from AI: ' . json_last_error_msg());
        }
        
        // Validate required structure
        $this->validateResponseStructure($decoded);
        
        return $decoded;
    }
    
    /**
     * Validate that AI response has required structure
     */
    private function validateResponseStructure(array $response): void
    {
        $requiredFields = ['type', 'data'];
        
        foreach ($requiredFields as $field) {
            if (!isset($response[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
        
        // Validate type
        $validTypes = ['text', 'table', 'chart', 'kpi'];
        if (!in_array($response['type'], $validTypes)) {
            throw new \Exception("Invalid response type: {$response['type']}");
        }
        
        // Validate data structure
        if (!is_array($response['data'])) {
            throw new \Exception("Data field must be an array");
        }
        
        // Ensure minimum required data fields
        if (!isset($response['data']['summary'])) {
            $response['data']['summary'] = 'Analysis completed based on your query.';
        }
        
        if (!isset($response['data']['details'])) {
            $response['data']['details'] = 'Detailed information has been processed from the database.';
        }
    }
    
    /**
     * Get fallback response when AI processing fails
     */
    private function getFallbackResponse(string $userQuestion, $queryResult): array
    {
        $resultCount = is_array($queryResult) ? count($queryResult) : (empty($queryResult) ? 0 : 1);
        
        if ($resultCount === 0) {
            return [
                'type' => 'text',
                'data' => [
                    'summary' => 'No data found for your query.',
                    'details' => 'The database search for "' . $userQuestion . '" returned no results. This could mean the requested information is not available or the criteria doesn\'t match any records.',
                    'charts' => [],
                    'tables' => [],
                    'kpis' => []
                ],
                'recommendations' => [
                    'Try rephrasing your question',
                    'Check if the data exists in the system',
                    'Contact support if you expect this data to be available'
                ]
            ];
        }
        
        // Basic formatting of the result
        if (is_array($queryResult) && count($queryResult) === 1) {
            $row = (array) $queryResult[0];
            $value = reset($row);
            $label = key($row);
            
            return [
                'type' => 'kpi',
                'data' => [
                    'summary' => "Found result for your query: {$label} is {$value}",
                    'details' => "Based on your question '{$userQuestion}', the system found: {$label} = {$value}",
                    'charts' => [],
                    'tables' => [],
                    'kpis' => [
                        [
                            'title' => $label,
                            'value' => (string) $value,
                            'formatted_value' => $this->formatValue($value),
                            'trend' => 'stable',
                            'change' => 'N/A',
                            'description' => "Current value for {$label}"
                        ]
                    ]
                ],
                'recommendations' => [
                    'Review this metric regularly',
                    'Compare with previous periods',
                    'Set targets for improvement'
                ]
            ];
        }
        
        return [
            'type' => 'text',
            'data' => [
                'summary' => "Found {$resultCount} results for your query.",
                'details' => "Your question '{$userQuestion}' returned {$resultCount} records from the database.",
                'charts' => [],
                'tables' => [],
                'kpis' => []
            ],
            'recommendations' => [
                'Review the detailed results',
                'Refine your query for more specific insights'
            ]
        ];
    }
    
    /**
     * Format numeric values for display
     */
    private function formatValue($value): string
    {
        if (!is_numeric($value)) {
            return (string) $value;
        }
        
        $num = (float) $value;
        
        if ($num >= 1000000000) {
            return number_format($num / 1000000000, 1) . 'B';
        } elseif ($num >= 1000000) {
            return number_format($num / 1000000, 1) . 'M';
        } elseif ($num >= 1000) {
            return number_format($num / 1000, 1) . 'K';
        } else {
            return number_format($num, is_float($num) ? 1 : 0);
        }
    }
}
