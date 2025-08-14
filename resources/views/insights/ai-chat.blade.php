@extends('layouts.admin')

@section('title', 'AI Assistant - Natural Language Analytics')

@section('page_title', 'AI Assistant')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('insights.dashboard') }}">Insights</a></li>
    <li class="breadcrumb-item active">AI Assistant</li>
</ol>
@endsection

@section('content')
<div class="ai-chat-interface">
    <div class="row">
        <!-- Chat Interface -->
        <div class="col-lg-8">
            <div class="card chat-card">
                <div class="card-header bg-gradient-dark text-white">
                    <div class="d-flex align-items-center">
                        <div class="ai-avatar mr-3">
                            <i class="fas fa-robot fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">ShuleSoft AI Assistant</h5>
                            <small class="opacity-75">Ask me anything about your schools and I'll provide insights</small>
                        </div>
                        <div class="ml-auto">
                            <div class="status-indicator">
                                <span class="badge badge-success">
                                    <i class="fas fa-circle pulse"></i> Online
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="chat-messages" id="chatMessages">
                        <!-- Welcome Message -->
                        <div class="message ai-message">
                            <div class="message-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="message-content">
                                <div class="message-bubble">
                                    <p class="mb-2">👋 Welcome! I'm your AI assistant for ShuleSoft Group Connect.</p>
                                    <p class="mb-2">I can help you analyze data across all your schools. Here are some things you can ask me:</p>
                                    <ul class="mb-2">
                                        <li>Performance comparisons between schools</li>
                                        <li>Financial analysis and fee collection insights</li>
                                        <li>Student enrollment and attendance trends</li>
                                        <li>Academic performance by subject or region</li>
                                        <li>Predictive analytics and recommendations</li>
                                    </ul>
                                    <p class="mb-0">What would you like to know?</p>
                                </div>
                                <div class="message-time">
                                    <small class="text-muted">Just now</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Type your question here..." id="messageInput" onkeypress="handleKeyPress(event)">
                        <div class="input-group-append">
                            <button class="btn btn-primary" onclick="sendMessage()" id="sendButton">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                    <div class="quick-actions mt-2">
                        <small class="text-muted mr-2">Quick actions:</small>
                        <button class="btn btn-outline-secondary btn-sm mr-1" onclick="insertQuickQuery('Show me the top 5 performing schools')">
                            <i class="fas fa-trophy mr-1"></i>Top Schools
                        </button>
                        <button class="btn btn-outline-secondary btn-sm mr-1" onclick="insertQuickQuery('What are the fee collection rates by region?')">
                            <i class="fas fa-dollar-sign mr-1"></i>Fee Collection
                        </button>
                        <button class="btn btn-outline-secondary btn-sm mr-1" onclick="insertQuickQuery('Analyze enrollment trends for this academic year')">
                            <i class="fas fa-chart-line mr-1"></i>Enrollment Trends
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="insertQuickQuery('Which schools need immediate attention?')">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Alerts
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar with Context & Tools -->
        <div class="col-lg-4">
            <!-- Data Sources -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-database mr-2"></i>Available Data Sources
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="data-source-list">
                        <div class="data-source-item">
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="source-icon mr-3">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Student Records</h6>
                                    <small class="text-muted">12,847 students across 24 schools</small>
                                </div>
                                <div class="status-dot bg-success"></div>
                            </div>
                        </div>
                        
                        <div class="data-source-item">
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="source-icon mr-3">
                                    <i class="fas fa-dollar-sign text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Financial Data</h6>
                                    <small class="text-muted">Revenue, expenses, fee collections</small>
                                </div>
                                <div class="status-dot bg-success"></div>
                            </div>
                        </div>
                        
                        <div class="data-source-item">
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="source-icon mr-3">
                                    <i class="fas fa-graduation-cap text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Academic Records</h6>
                                    <small class="text-muted">Grades, subjects, performance</small>
                                </div>
                                <div class="status-dot bg-success"></div>
                            </div>
                        </div>
                        
                        <div class="data-source-item">
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="source-icon mr-3">
                                    <i class="fas fa-calendar-check text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Attendance Data</h6>
                                    <small class="text-muted">Daily attendance records</small>
                                </div>
                                <div class="status-dot bg-success"></div>
                            </div>
                        </div>
                        
                        <div class="data-source-item">
                            <div class="d-flex align-items-center p-3">
                                <div class="source-icon mr-3">
                                    <i class="fas fa-school text-secondary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">School Operations</h6>
                                    <small class="text-muted">Staff, resources, infrastructure</small>
                                </div>
                                <div class="status-dot bg-success"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Queries -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-history mr-2"></i>Recent Queries
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="recent-queries-list">
                        <div class="query-item p-3 border-bottom clickable" onclick="insertQuickQuery('Which schools have the highest revenue this month?')">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-search text-muted mr-2"></i>
                                <div class="flex-grow-1">
                                    <small>Which schools have the highest revenue this month?</small>
                                </div>
                            </div>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                        
                        <div class="query-item p-3 border-bottom clickable" onclick="insertQuickQuery('Show attendance trends for North Eastern region')">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-search text-muted mr-2"></i>
                                <div class="flex-grow-1">
                                    <small>Show attendance trends for North Eastern region</small>
                                </div>
                            </div>
                            <small class="text-muted">Yesterday</small>
                        </div>
                        
                        <div class="query-item p-3 border-bottom clickable" onclick="insertQuickQuery('Compare mathematics performance across all schools')">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-search text-muted mr-2"></i>
                                <div class="flex-grow-1">
                                    <small>Compare mathematics performance across all schools</small>
                                </div>
                            </div>
                            <small class="text-muted">2 days ago</small>
                        </div>
                        
                        <div class="query-item p-3 clickable" onclick="insertQuickQuery('Predict enrollment for next quarter')">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-search text-muted mr-2"></i>
                                <div class="flex-grow-1">
                                    <small>Predict enrollment for next quarter</small>
                                </div>
                            </div>
                            <small class="text-muted">3 days ago</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- AI Capabilities -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-brain mr-2"></i>AI Capabilities
                    </h6>
                </div>
                <div class="card-body">
                    <div class="capability-list">
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-bar text-primary mr-2"></i>
                                <span class="font-weight-bold">Data Analysis</span>
                            </div>
                            <small class="text-muted">Analyze patterns, trends, and correlations across all your data</small>
                        </div>
                        
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-crystal-ball text-success mr-2"></i>
                                <span class="font-weight-bold">Predictive Insights</span>
                            </div>
                            <small class="text-muted">Forecast enrollment, revenue, and performance trends</small>
                        </div>
                        
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lightbulb text-warning mr-2"></i>
                                <span class="font-weight-bold">Recommendations</span>
                            </div>
                            <small class="text-muted">Get actionable insights to improve operations</small>
                        </div>
                        
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                                <span class="font-weight-bold">Anomaly Detection</span>
                            </div>
                            <small class="text-muted">Identify unusual patterns that need attention</small>
                        </div>
                        
                        <div class="capability-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-comments text-info mr-2"></i>
                                <span class="font-weight-bold">Natural Language</span>
                            </div>
                            <small class="text-muted">Ask questions in plain English and get clear answers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sample Query Modal -->
<div class="modal fade" id="sampleQueriesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sample Questions</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Financial Analysis</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" onclick="insertQuickQuery('Which schools have outstanding fees above $10,000?')" data-dismiss="modal">Schools with high fee arrears</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Show revenue comparison between regions')" data-dismiss="modal">Regional revenue comparison</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Calculate profit margins for each school')" data-dismiss="modal">Profit margin analysis</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Predict next quarter revenue')" data-dismiss="modal">Revenue forecasting</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Academic Performance</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" onclick="insertQuickQuery('Which subjects need improvement across all schools?')" data-dismiss="modal">Subject performance analysis</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Compare exam results by grade level')" data-dismiss="modal">Grade-level comparison</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Show trends in student progression rates')" data-dismiss="modal">Student progression trends</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Identify underperforming schools')" data-dismiss="modal">Underperforming schools</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Operational Insights</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" onclick="insertQuickQuery('Show teacher-to-student ratios by school')" data-dismiss="modal">Teacher-student ratios</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Analyze attendance patterns by month')" data-dismiss="modal">Attendance patterns</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Which schools have capacity for more students?')" data-dismiss="modal">Capacity analysis</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Show resource allocation efficiency')" data-dismiss="modal">Resource allocation</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Strategic Planning</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" onclick="insertQuickQuery('What are the growth opportunities by region?')" data-dismiss="modal">Growth opportunities</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Identify schools that need investment')" data-dismiss="modal">Investment priorities</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Analyze competitive positioning in each region')" data-dismiss="modal">Competitive analysis</a></li>
                            <li><a href="#" onclick="insertQuickQuery('Recommend optimal fee structures')" data-dismiss="modal">Fee structure optimization</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let messageCounter = 0;

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (message === '') return;
    
    // Add user message to chat
    addMessage(message, 'user');
    
    // Clear input and show loading
    input.value = '';
    showTypingIndicator();
    
    // Send to AI processing
    processAIQuery(message);
}

function addMessage(message, sender) {
    const chatMessages = document.getElementById('chatMessages');
    const messageId = 'message-' + (++messageCounter);
    const timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    const messageHTML = `
        <div class="message ${sender}-message" id="${messageId}">
            <div class="message-avatar">
                ${sender === 'user' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>'}
            </div>
            <div class="message-content">
                <div class="message-bubble">
                    ${formatMessage(message)}
                </div>
                <div class="message-time">
                    <small class="text-muted">${timestamp}</small>
                </div>
            </div>
        </div>
    `;
    
    chatMessages.insertAdjacentHTML('beforeend', messageHTML);
    scrollToBottom();
}

function formatMessage(message) {
    // Check if message contains structured data (JSON)
    try {
        const data = JSON.parse(message);
        return formatStructuredResponse(data);
    } catch {
        // Regular text message
        return `<p class="mb-0">${message}</p>`;
    }
}

function formatStructuredResponse(data) {
    let html = `<p class="mb-2">${data.answer}</p>`;
    
    if (data.confidence) {
        html += `
            <div class="confidence-indicator mb-2">
                <small class="text-muted">Confidence: </small>
                <div class="progress d-inline-block" style="width: 80px; height: 8px;">
                    <div class="progress-bar bg-success" style="width: ${data.confidence}%"></div>
                </div>
                <small class="text-success ml-1">${data.confidence}%</small>
            </div>
        `;
    }
    
    if (data.data_sources && data.data_sources.length > 0) {
        html += `
            <div class="data-sources mb-2">
                <small class="text-muted">Data sources: </small>
                ${data.data_sources.map(source => `<span class="badge badge-light badge-sm">${source}</span>`).join(' ')}
            </div>
        `;
    }
    
    if (data.suggestions && data.suggestions.length > 0) {
        html += `
            <div class="suggestions">
                <small class="text-muted d-block mb-1">Related actions:</small>
                ${data.suggestions.map(suggestion => `
                    <button class="btn btn-outline-primary btn-sm mr-1 mb-1" onclick="insertQuickQuery('${suggestion}')">
                        ${suggestion}
                    </button>
                `).join('')}
            </div>
        `;
    }
    
    return html;
}

function showTypingIndicator() {
    const chatMessages = document.getElementById('chatMessages');
    const typingHTML = `
        <div class="message ai-message typing-indicator" id="typing">
            <div class="message-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">
                <div class="message-bubble">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    chatMessages.insertAdjacentHTML('beforeend', typingHTML);
    scrollToBottom();
}

function removeTypingIndicator() {
    const typing = document.getElementById('typing');
    if (typing) {
        typing.remove();
    }
}

function processAIQuery(query) {
    fetch('/insights/ai-query', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ query: query })
    })
    .then(response => response.json())
    .then(data => {
        removeTypingIndicator();
        
        // Simulate different types of responses based on query
        let response = generateContextualResponse(query, data);
        
        addMessage(JSON.stringify(response), 'ai');
    })
    .catch(error => {
        removeTypingIndicator();
        console.error('Error:', error);
        addMessage('I apologize, but I encountered an error processing your request. Please try again.', 'ai');
    });
}

function generateContextualResponse(query, baseData) {
    const lowerQuery = query.toLowerCase();
    
    // Financial queries
    if (lowerQuery.includes('revenue') || lowerQuery.includes('fees') || lowerQuery.includes('financial')) {
        return {
            answer: "Based on current financial data, Greenfield Academy leads with $185,000 monthly revenue, while Coast Academy shows concerning fee collection at only 65% (compared to group average of 89.7%). Three schools in North Eastern region have arrears above $15,000.",
            confidence: 94,
            data_sources: ['revenues_table', 'payments_table', 'fees_table'],
            suggestions: ['View detailed financial report', 'Set up payment reminders', 'Schedule finance review meeting']
        };
    }
    
    // Academic performance queries
    if (lowerQuery.includes('performance') || lowerQuery.includes('academic') || lowerQuery.includes('grades') || lowerQuery.includes('exam')) {
        return {
            answer: "Academic analysis shows group average of 78.5% with 92.8% pass rate. Mathematics needs improvement (74.2% average) while Social Studies excels (82.1%). Greenfield Academy leads academic performance at 96.8% overall score.",
            confidence: 89,
            data_sources: ['grades_table', 'exams_table', 'subjects_table'],
            suggestions: ['View subject breakdown', 'Generate academic report', 'Schedule teacher training']
        };
    }
    
    // Enrollment/attendance queries
    if (lowerQuery.includes('enrollment') || lowerQuery.includes('students') || lowerQuery.includes('attendance')) {
        return {
            answer: "Current enrollment stands at 12,847 students with 94.2% average attendance. Enrollment grew 8.5% year-over-year. North Eastern region shows lower attendance (78%) requiring attention, while Nairobi maintains 96% attendance rate.",
            confidence: 96,
            data_sources: ['student_table', 'attendance_table', 'enrollment_records'],
            suggestions: ['View enrollment trends', 'Analyze attendance patterns', 'Create attendance improvement plan']
        };
    }
    
    // Regional or comparison queries
    if (lowerQuery.includes('region') || lowerQuery.includes('compare') || lowerQuery.includes('best') || lowerQuery.includes('worst')) {
        return {
            answer: "Regional analysis: Nairobi leads overall (91.3% performance) with 8 schools, while North Eastern needs support (76.5% performance) with 3 schools. Central region shows highest efficiency with 91.2% academic performance despite smaller size.",
            confidence: 92,
            data_sources: ['schools_table', 'regional_data', 'performance_metrics'],
            suggestions: ['View regional comparison chart', 'Create regional improvement plan', 'Schedule regional review']
        };
    }
    
    // Prediction/forecasting queries
    if (lowerQuery.includes('predict') || lowerQuery.includes('forecast') || lowerQuery.includes('future') || lowerQuery.includes('next')) {
        return {
            answer: "Predictive models show enrollment reaching 13,150 next month (87% confidence) and revenue projected at $398,000. Year-end forecasts: 14,250 students and $5.18M revenue. Risk assessment indicates 3 schools need monitoring.",
            confidence: 87,
            data_sources: ['historical_data', 'trend_analysis', 'predictive_models'],
            suggestions: ['View detailed forecasts', 'Adjust capacity planning', 'Review risk assessments']
        };
    }
    
    // Default response
    return baseData;
}

function insertQuickQuery(query) {
    document.getElementById('messageInput').value = query;
    document.getElementById('messageInput').focus();
}

function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Auto-scroll on new messages
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
            scrollToBottom();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    observer.observe(chatMessages, { childList: true });
    
    // Focus on input
    document.getElementById('messageInput').focus();
});
</script>
@endsection

@section('styles')
<style>
.ai-chat-interface .chat-card {
    height: calc(100vh - 200px);
    display: flex;
    flex-direction: column;
}

.ai-chat-interface .chat-messages {
    height: 500px;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
}

.ai-chat-interface .message {
    display: flex;
    margin-bottom: 20px;
    align-items: flex-start;
}

.ai-chat-interface .message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.ai-chat-interface .ai-message .message-avatar {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    margin-right: 15px;
}

.ai-chat-interface .user-message .message-avatar {
    background: linear-gradient(45deg, #28a745, #1e7e34);
    color: white;
    margin-left: 15px;
    order: 2;
}

.ai-chat-interface .message-content {
    flex-grow: 1;
    max-width: 70%;
}

.ai-chat-interface .user-message .message-content {
    text-align: right;
}

.ai-chat-interface .message-bubble {
    background: white;
    padding: 15px 20px;
    border-radius: 18px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    position: relative;
}

.ai-chat-interface .user-message .message-bubble {
    background: #007bff;
    color: white;
}

.ai-chat-interface .message-time {
    margin-top: 5px;
    font-size: 11px;
}

.ai-chat-interface .user-message .message-time {
    text-align: right;
}

.ai-chat-interface .typing-dots {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 0;
}

.ai-chat-interface .typing-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #007bff;
    margin: 0 2px;
    animation: typing 1.4s infinite ease-in-out;
}

.ai-chat-interface .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.ai-chat-interface .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
    40% { transform: scale(1); opacity: 1; }
}

.ai-chat-interface .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.ai-chat-interface .pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.ai-chat-interface .confidence-indicator {
    display: flex;
    align-items: center;
}

.ai-chat-interface .quick-actions .btn {
    border-radius: 15px;
    font-size: 12px;
}

.ai-chat-interface .clickable {
    cursor: pointer;
    transition: background-color 0.2s;
}

.ai-chat-interface .clickable:hover {
    background-color: #f8f9fa;
}

.ai-chat-interface .data-source-item,
.ai-chat-interface .query-item {
    transition: background-color 0.2s;
}

.ai-chat-interface .data-source-item:hover,
.ai-chat-interface .query-item:hover {
    background-color: #f1f3f4;
}

.ai-chat-interface .capability-item {
    padding: 10px;
    border-left: 3px solid #e9ecef;
    margin-left: 10px;
    transition: border-color 0.2s;
}

.ai-chat-interface .capability-item:hover {
    border-left-color: #007bff;
}

.ai-chat-interface .bg-gradient-dark {
    background: linear-gradient(45deg, #343a40, #23272b);
}

@media (max-width: 768px) {
    .ai-chat-interface .message-content {
        max-width: 85%;
    }
    
    .ai-chat-interface .chat-messages {
        height: 400px;
    }
    
    .ai-chat-interface .quick-actions .btn {
        font-size: 10px;
        padding: 4px 8px;
    }
}
</style>
@endsection
