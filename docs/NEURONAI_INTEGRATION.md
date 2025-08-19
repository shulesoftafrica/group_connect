# NeuronAI + Laravel Agent Integration

## Overview
This document outlines the complete integration of NeuronAI with Laravel Agent for the Group Connect school management system. The integration provides AI-powered natural language querying of school data with structured JSON responses.

## Features Implemented

### 🤖 AI-Powered Natural Language Interface
- **Natural Language Processing**: Users can ask questions in plain English
- **Context-Aware Responses**: AI understands school management context
- **Conversation History**: Maintains context across multiple queries
- **Intent Detection**: Automatically detects query type (revenue, enrollment, expenses, etc.)

### 📊 Structured Response System
- **Multiple Response Types**: text, table, chart, kpi
- **Dynamic Visualizations**: Auto-generates charts using Chart.js
- **Data Tables**: Renders structured data in responsive tables
- **KPI Cards**: Displays key performance indicators with trend indicators

### 💰 Premium Subscription Model
- **Free Tier**: 3 AI reports per month
- **Premium Tier**: Unlimited reports at TSh 50,000 per school/month
- **Usage Tracking**: Automatic rate limiting and upgrade prompts
- **Upgrade Modal**: Seamless subscription flow

### 🔐 Security & Performance
- **Rate Limiting**: Prevents abuse with session-based limits
- **Error Handling**: Comprehensive error handling with fallback responses
- **API Key Security**: Secure OpenAI API key management
- **Database Protection**: Parameterized queries prevent SQL injection

## Technical Architecture

### Backend Components

#### 1. InsightsController (`app/Http/Controllers/InsightsController.php`)
Main controller handling AI processing:

```php
// Key Methods:
- processAIQuery()           // Main AI processing endpoint
- processWithAIAgent()       // AI agent orchestration
- buildDatabaseContext()     // Database schema context building
- buildSystemPrompt()        // AI system prompt generation
- callAIWithMCP()           // OpenAI API integration
- structureAIResponse()     // Response formatting
```

#### 2. Configuration (`config/services.php`)
OpenAI service configuration:

```php
'openai' => [
    'key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION', null),
    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    'max_tokens' => env('OPENAI_MAX_TOKENS', 2000),
    'temperature' => env('OPENAI_TEMPERATURE', 0.3),
],
```

#### 3. Routes (`routes/web.php`)
```php
Route::get('/insights', [InsightsController::class, 'dashboard'])->name('insights.dashboard');
Route::post('/insights/ai-query', [InsightsController::class, 'processAIQuery'])->name('insights.ai-query');
```

### Frontend Components

#### 1. AI Chat Interface (`resources/views/insights/dashboard.blade.php`)
- **Chat Container**: Real-time conversation interface
- **Visualization Panel**: Dynamic chart and table rendering
- **Suggested Prompts**: Quick-start query examples
- **Usage Tracking**: Visual usage counter and upgrade prompts

#### 2. JavaScript Features
- **AJAX Communication**: Real-time AI query processing
- **Chart Rendering**: Chart.js integration for data visualization
- **Typing Indicators**: Enhanced UX with loading states
- **Download Functionality**: Export charts and tables
- **Conversation History**: Context management across queries

## Environment Setup

### Required Environment Variables
Add to your `.env` file:

```bash
# OpenAI Configuration for NeuronAI
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_ORGANIZATION=your_org_id_optional
OPENAI_MODEL=gpt-4o-mini
OPENAI_MAX_TOKENS=2000
OPENAI_TEMPERATURE=0.3
```

### Dependencies
- **Laravel Framework**: 12.23.1+
- **PostgreSQL**: Database engine
- **laravel/boost**: 1.0.18+ (for MCP integration)
- **laravel/mcp**: 0.1.1+ (Model Context Protocol)

## Database Integration

### Supported Tables
The AI can query and analyze data from:
- `students` - Student enrollment data
- `payments` - Payment transactions
- `fees` - Fee structure and billing
- `schools` - School information
- `expenses` - Expense tracking
- `revenues` - Revenue analysis
- `invoices` - Invoice management
- `attendance` - Attendance records

### Query Examples
```sql
-- Revenue Analysis
SELECT SUM(amount) FROM payments WHERE payment_date >= date_trunc('month', CURRENT_DATE)

-- Student Enrollment
SELECT COUNT(*) FROM students WHERE status = 'active'

-- Payment Success Rate
SELECT COUNT(*), SUM(amount) FROM payments WHERE status = 'completed'
```

## API Endpoints

### POST `/insights/ai-query`
Process natural language queries with AI.

**Request:**
```json
{
    "query": "Show me revenue trends this month",
    "conversation_history": [
        {"role": "user", "content": "Previous query"},
        {"role": "assistant", "content": "Previous response"}
    ]
}
```

**Response:**
```json
{
    "success": true,
    "response": {
        "type": "chart",
        "data": {
            "summary": "Revenue analysis for current month",
            "details": "Detailed analysis...",
            "charts": [{"type": "bar", "data": {...}}]
        },
        "recommendations": ["Actionable insights"],
        "sql_query": "SELECT ..."
    },
    "query": "Original query",
    "timestamp": "2025-08-19T10:30:00Z"
}
```

## Usage Examples

### Revenue Analysis
**Query**: "Show me total revenue this month"
**Response**: KPI cards with revenue figures, trends, and recommendations

### Student Enrollment
**Query**: "How many students are enrolled this year?"
**Response**: Text summary with enrollment statistics and growth trends

### Payment Collections
**Query**: "What's our payment collection rate?"
**Response**: Chart showing payment trends with success rates

### Expense Tracking
**Query**: "Break down expenses by category"
**Response**: Table with expense categories and spending analysis

## Rate Limiting & Monetization

### Free Tier Limitations
- 3 AI reports per session per month
- Basic visualizations
- Standard response time

### Premium Features (TSh 50,000/school/month)
- Unlimited AI reports
- Advanced visualizations
- Priority processing
- Export functionality
- Custom report scheduling

## Error Handling

### Common Error Scenarios
1. **API Key Issues**: Clear error messages with setup instructions
2. **Rate Limiting**: Automatic upgrade prompts when limits reached
3. **Database Errors**: Fallback responses with sample data
4. **Network Issues**: Graceful degradation with cached responses

### Fallback Responses
When AI processing fails, the system provides intelligent fallback responses based on query intent:
- Revenue queries → Sample revenue KPIs
- Enrollment queries → General enrollment information
- Payment queries → Payment system status

## Monitoring & Debugging

### Logging
- AI query processing logged in `storage/logs/laravel.log`
- API call success/failure tracking
- User query patterns for optimization

### Debug Mode
In local environment, detailed error messages are returned:
```php
'error' => app()->environment('local') ? $e->getMessage() : null
```

## Security Considerations

### API Key Protection
- OpenAI API key stored in environment variables
- Never exposed in frontend code
- Configurable per environment

### Rate Limiting
- Session-based usage tracking
- Prevents API abuse
- Automatic cleanup of old usage records

### Data Privacy
- No sensitive data sent to OpenAI
- Only schema and aggregated statistics shared
- User queries logged for improvement (can be disabled)

## Performance Optimization

### Caching Strategy
- Usage counters cached in Laravel cache
- Database query results cached for repeated patterns
- Static responses cached for common queries

### Response Time
- Average response time: 2-5 seconds
- Timeout configured at 30 seconds
- Background processing for complex queries

## Future Enhancements

### Planned Features
1. **Multi-School Analysis**: Cross-school comparisons
2. **Scheduled Reports**: Automated report generation
3. **Custom Dashboards**: User-defined visualizations
4. **Export Options**: PDF, Excel, CSV exports
5. **Mobile App Integration**: Native mobile AI chat

### AI Model Upgrades
- Support for GPT-4 for complex analysis
- Custom fine-tuned models for education domain
- Local AI model deployment for data privacy

## Troubleshooting

### Common Issues

#### 1. "OpenAI API key not configured"
**Solution**: Add `OPENAI_API_KEY` to `.env` file

#### 2. "Method does not exist" HTTP errors
**Solution**: Ensure Laravel HTTP client is properly configured

#### 3. Database connection errors
**Solution**: Verify database configuration and table existence

#### 4. Rate limit exceeded
**Solution**: Either wait for monthly reset or upgrade to premium

### Support Commands
```bash
# Clear configuration cache
php artisan config:clear

# Check routes
php artisan route:list | grep insights

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()

# View logs
tail -f storage/logs/laravel.log
```

## License & Credits

This NeuronAI integration is part of the Group Connect school management system developed by Shulesoft Africa. 

**Technologies Used:**
- OpenAI GPT-4o-mini
- Laravel Framework
- Chart.js for visualizations
- Bootstrap for UI components
- PostgreSQL for data storage

---

**Last Updated**: August 19, 2025
**Version**: 1.0.0
**Support**: Contact Shulesoft Africa for technical support
