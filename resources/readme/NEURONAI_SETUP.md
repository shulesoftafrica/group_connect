# NeuronAI + Laravel Agent Setup Guide

## Overview
This system integrates NeuronAI with Laravel Agent using OpenAI's API to provide database-aware natural language processing for school management insights.

## Features
- **Natural Language Queries**: Ask questions like "Show me this month's revenue" or "Which students have outstanding fees?"
- **Structured Responses**: Get organized data as text, tables, charts, or KPIs
- **Database Integration**: AI queries your actual PostgreSQL database
- **Rate Limiting**: Free tier (3 reports/month) with premium upgrade (Tsh 50,000/school/month)
- **Real-time Visualizations**: Charts and tables generated automatically
- **Conversation History**: Context-aware responses
- **Recommendations**: Actionable insights included in responses

## Setup Instructions

### 1. OpenAI API Configuration
1. Get an OpenAI API key from https://platform.openai.com/api-keys
2. Add the following to your `.env` file:
```env
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_ORGANIZATION=your_org_id_here (optional)
OPENAI_MODEL=gpt-4o-mini
OPENAI_MAX_TOKENS=2000
OPENAI_TEMPERATURE=0.3
```

### 2. Required Dependencies
The following packages are already installed:
- `laravel/boost` (v1.0.18) - Laravel MCP integration
- `laravel/mcp` (v0.1.1) - Model Context Protocol support

### 3. Database Requirements
Ensure your PostgreSQL database has the following tables (already in your schema):
- `students` - Student enrollment data
- `payments` - Payment records
- `fees` - Fee structure
- `schools` - School information
- `expenses` - Expense tracking
- `revenues` - Revenue records
- `invoices` - Invoice data
- `attendance` - Attendance records

### 4. Multi-tenant Configuration
Update the `getCurrentSchool()` method in `InsightsController.php` to match your multi-tenant setup:

```php
private function getCurrentSchool()
{
    // Replace with your actual multi-tenant logic
    $user = auth()->user();
    return $user->school; // or however you get the current school
}
```

## Usage Examples

### Sample Queries
- "Show me revenue trends for the last 6 months"
- "Which students have outstanding fee payments?"
- "Compare enrollment across all grades"
- "What are our top 5 expense categories?"
- "Generate attendance report for this week"
- "Show me students with declining performance"

### Response Format
The AI returns structured JSON responses:
```json
{
    "type": "chart|table|kpi|text",
    "data": {
        "summary": "Brief summary of findings",
        "details": "Detailed analysis",
        "charts": [], // Chart data for visualizations
        "tables": [], // Table data
        "kpis": []    // Key performance indicators
    },
    "recommendations": ["actionable recommendations"],
    "sql_query": "SQL query used for transparency"
}
```

## API Endpoints

### Main Dashboard
- **URL**: `/insights`
- **Method**: GET
- **Description**: Display the AI Reports & Insights interface

### AI Query Processing
- **URL**: `/insights/ai-query`
- **Method**: POST
- **Parameters**:
  - `query` (required): Natural language question
  - `conversation_history` (optional): Previous conversation context
- **Response**: Structured AI response with data and visualizations

## Rate Limiting & Pricing

### Free Tier
- 3 AI reports per month per session
- Basic visualizations
- Standard response time

### Premium Tier (Tsh 50,000/school/month)
- Unlimited AI reports
- Advanced visualizations
- Priority processing
- Export functionality
- Extended conversation history

## Architecture

### Flow
1. User enters natural language query
2. System builds database context (tables, current stats, school info)
3. AI processes query with database context
4. Response structured and returned to frontend
5. Frontend renders appropriate visualization (chart/table/KPI)

### Components
- **InsightsController**: Main controller handling AI requests
- **AI Agent Methods**: Database context building, AI calling, response structuring
- **Frontend Interface**: Chat interface with visualization panel
- **Rate Limiting**: Session-based usage tracking
- **Caching**: Monthly usage counters

## Troubleshooting

### Common Issues
1. **"OpenAI API key not configured"**: Add `OPENAI_API_KEY` to your `.env` file
2. **"AI service error"**: Check your OpenAI API key validity and quota
3. **"Database queries fail"**: Verify database connection and table existence
4. **Rate limit reached**: Wait for next month or upgrade to premium

### Debug Mode
Set `APP_DEBUG=true` in `.env` to see detailed error messages in local development.

### Logs
Check `storage/logs/laravel.log` for:
- AI Query Processing Errors
- AI Agent Processing Errors
- MCP AI Call Errors
- Response Structuring Errors

## Security

- All database queries are read-only (SELECT, SHOW, EXPLAIN, DESCRIBE)
- Input validation prevents injection attacks
- CSRF protection on all AI query endpoints
- Rate limiting prevents abuse
- Session-based tracking (no personal data stored)

## Customization

### Adding New Query Types
Extend the `detectQueryIntent()` method in `InsightsController.php`:
```php
$intents = [
    'revenue' => ['revenue', 'income', 'money'],
    'enrollment' => ['student', 'enrollment', 'admission'],
    'your_custom_type' => ['keyword1', 'keyword2']
];
```

### Modifying AI Prompts
Update the `buildSystemPrompt()` method to customize how AI interprets queries and generates responses.

### Custom Visualizations
Extend the frontend JavaScript to handle additional chart types or data formats.

---

**Support**: For technical issues or feature requests, please contact the development team.
