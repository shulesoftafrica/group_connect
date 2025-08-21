<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SqlGeneratorService
{
    private $openAIService;
    
    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }
    
    /**
     * Generate SQL query from user question and database schema
     * 
     * @param string $userQuestion Natural language question from user
     * @param array $schema Database schema information
     * @return string Raw SQL query
     */
    public function generateQuery(string $userQuestion, array $schema): string
    {
        try {
            // Build minimal, SQL-only prompt
            $prompt = $this->buildSqlPrompt($userQuestion, $schema);
            
            Log::info('SQL Generator - User Question', ['question' => $userQuestion]);
            Log::info('SQL Generator - Prompt', ['prompt' => $prompt]);
            
            // Call AI to generate SQL
            $sqlResponse = $this->callAI($prompt);
            
            // Clean and validate the SQL
            $cleanSql = $this->cleanSqlResponse($sqlResponse);
            
            Log::info('SQL Generator - Generated Query', ['sql' => $cleanSql]);
            
            return $cleanSql;
            
        } catch (\Exception $e) {
            Log::error('SQL Generator Error', [
                'question' => $userQuestion,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Failed to generate SQL query: ' . $e->getMessage());
        }
    }
    
    /**
     * Build minimal, SQL-only prompt for AI
     */
    private function buildSqlPrompt(string $userQuestion, array $schema): string
    {
        $currentSchools = $schema['current_schools'] ?? [];
        $tables = $schema['tables'] ?? [];
        
        // Build schema filter
        $schemaFilter = count($currentSchools) > 1
            ? "WHERE schema_name IN ('" . implode("','", $currentSchools) . "')"
            : "WHERE schema_name = '" . ($currentSchools[0] ?? '') . "'";
        
        $tablesInfo = '';
        foreach ($tables as $tableName => $tableDescription) {
            $tablesInfo .= "- {$tableName}: {$tableDescription}\n";
        }
        
        return <<<PROMPT
Based on the following PostgreSQL database tables and user request, provide only the SQL query. Do not add any text, explanations, or JSON formatting. Just the raw SQL query.

TABLES:
{$tablesInfo}

SECURITY REQUIREMENTS:
- MUST include this filter in WHERE clause: {$schemaFilter}
- Only SELECT queries are allowed
- No INSERT, UPDATE, DELETE, DROP, CREATE operations
- Query must be safe for read-only execution

USER REQUEST: {$userQuestion}

Response format: Return ONLY the SQL query, nothing else.
PROMPT;
    }
    
    /**
     * Call AI service to generate SQL
     */
    private function callAI(string $prompt): string
    {
        try {
            $config = [
                'temperature' => 0.1, // Low temperature for consistent SQL generation
                'max_tokens' => 500,   // SQL queries should be concise
            ];
            
            $response = $this->openAIService->chatCompletion($prompt, $config);
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('AI Call Error in SQL Generator', ['error' => $e->getMessage()]);
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
     * Clean and validate SQL response from AI
     */
    private function cleanSqlResponse(string $sqlResponse): string
    {
        // Remove any extra text, formatting, or markdown
        $sql = trim($sqlResponse);
        
        // Remove code block markers if present
        $sql = preg_replace('/^```sql?\s*/i', '', $sql);
        $sql = preg_replace('/```\s*$/', '', $sql);
        
        // Remove any explanatory text before or after the SQL
        $lines = explode("\n", $sql);
        $sqlLines = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Skip lines that look like explanations
            if (preg_match('/^(this|the|here|above|below|query|result)/i', $line)) {
                continue;
            }
            
            $sqlLines[] = $line;
        }
        
        $cleanSql = implode(' ', $sqlLines);
        
        // Basic validation
        if (!preg_match('/^\s*SELECT\s+/i', $cleanSql)) {
            throw new \Exception('Generated query is not a valid SELECT statement');
        }
        
        // Ensure it ends properly
        if (!str_ends_with(trim($cleanSql), ';')) {
            $cleanSql = trim($cleanSql) . ';';
        }
        
        return $cleanSql;
    }
    
    /**
     * Validate that SQL is safe for execution
     */
    public function validateSqlSafety(string $sql): bool
    {
   
        $sql = strtoupper(trim($sql));
        
        // Must start with SELECT
        if (!str_starts_with(strtoupper($sql), 'SELECT')) {
            return false;
        }
        
        // Block dangerous keywords
        $dangerousKeywords = [
            'INSERT', 'UPDATE', 'DELETE', 'DROP', 'CREATE', 'ALTER', 'TRUNCATE',
            'GRANT', 'REVOKE', 'EXEC', 'EXECUTE', 'CALL', 'DECLARE',
            'MERGE', 'REPLACE', 'UPSERT', 'COPY', 'BULK'
        ];
        
        foreach ($dangerousKeywords as $keyword) {
            if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/i', $sql)) {
                return false;
            }
        }
        
        // Must contain schema filter for security in the WHERE clause
        if (!preg_match('/\bWHERE\b.*\bschema_name\s+(=|IN)\s*\(/i', $sql)) {
            return false;
        }
        
        return true;
    }
}
