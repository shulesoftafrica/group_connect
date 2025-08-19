@extends('layouts.admin')

@section('title', 'Reports & Insights')

@section('content')
<div id="reports-insights-app" class="h-full bg-gray-50">
    <!-- Loading state -->
    <div id="loading-state" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
        <div class="text-center">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Loading Reports & Insights...</p>
        </div>
    </div>

    <!-- Main App Container -->
    <div class="flex h-screen bg-gray-50" style="display: none;" id="main-app">
        <!-- Left Panel - Chat Interface -->
        <div class="flex-1 flex flex-col bg-white border-r border-gray-200">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">Reports & Insights</h1>
                <p class="text-sm text-gray-600 mt-1">Ask questions about your school data in natural language</p>
                
                <!-- Usage Notice -->
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                <span class="font-medium">Free Plan:</span> 
                                <span id="reports-used">{{ $free_reports_used }}</span> of {{ $free_reports_limit }} reports used this month.
                                <a href="#" class="underline font-medium" onclick="showUpgradeModal()">
                                    Upgrade to Premium for unlimited access at Tsh 50,000/school/month.
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Messages Area -->
            <div class="flex-1 overflow-y-auto p-6" id="chat-messages">
                <!-- Welcome Message -->
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-4">
                        <h3 class="font-semibold mb-2">Welcome to AI-Powered Insights! 👋</h3>
                        <p class="text-sm opacity-90">
                            I can help you analyze data across all your schools. Ask me anything about revenue, enrollment, 
                            attendance, communication stats, or academic performance.
                        </p>
                    </div>
                </div>

                <!-- Conversation History -->
                <div id="conversation-history">
                    @foreach($conversation_history as $conversation)
                    <div class="mb-6">
                        <!-- User Message -->
                        <div class="flex justify-end mb-3">
                            <div class="bg-blue-600 text-white rounded-lg px-4 py-2 max-w-md">
                                <p class="text-sm">{{ $conversation['query'] }}</p>
                                <span class="text-xs opacity-75 block mt-1">{{ \Carbon\Carbon::parse($conversation['timestamp'])->format('M j, g:i A') }}</span>
                            </div>
                        </div>

                        <!-- AI Response -->
                        <div class="flex justify-start">
                            <div class="bg-gray-100 text-gray-900 rounded-lg px-4 py-3 max-w-2xl">
                                <div class="response-content" data-response="{{ json_encode($conversation['response']) }}">
                                    <!-- Response will be rendered by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Suggested Prompts -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-3">Try these suggested prompts:</p>
                <div class="flex flex-wrap gap-2 mb-4" id="suggested-prompts">
                    @foreach($suggested_prompts as $prompt)
                    <button 
                        class="suggested-prompt-btn bg-white border border-gray-300 rounded-full px-3 py-1 text-sm text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors"
                        onclick="fillPrompt('{{ $prompt }}')">
                        {{ $prompt }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Chat Input -->
            <div class="bg-white border-t border-gray-200 px-6 py-4">
                <form id="chat-form" class="flex gap-3">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            id="chat-input" 
                            placeholder="Ask me anything about your school data..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            maxlength="1000"
                        >
                    </div>
                    <button 
                        type="submit" 
                        id="send-btn"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="send-text">Send</span>
                        <span class="loading-text hidden">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-2">Press Enter to send • Max 1000 characters</p>
            </div>
        </div>

        <!-- Right Panel - Insights Display -->
        <div class="w-1/2 bg-white flex flex-col" id="insights-panel">
            <!-- Panel Header -->
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Data Visualizations</h2>
                    <div class="flex gap-2">
                        <button 
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                            onclick="clearInsights()"
                            title="Clear visualizations">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        <button 
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                            onclick="toggleInsightsPanel()"
                            title="Toggle panel">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Panel Content -->
            <div class="flex-1 overflow-y-auto" id="insights-content">
                <div class="p-6 text-center text-gray-500">
                    <svg class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-lg font-medium mb-2">Ready for Insights</p>
                    <p class="text-sm">Charts and data tables will appear here when you ask questions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upgrade Modal -->
<div id="upgrade-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Upgrade to Premium</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Get unlimited AI-powered reports and insights for just <strong>Tsh 50,000 per school per month</strong>.
                </p>
                <div class="mt-4 text-left">
                    <h4 class="font-medium text-gray-900 mb-2">Premium Features:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Unlimited AI reports and queries</li>
                        <li>• Advanced data visualizations</li>
                        <li>• Export to PDF and Excel</li>
                        <li>• Priority support</li>
                        <li>• Custom report scheduling</li>
                    </ul>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <button 
                    class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Contact Sales
                </button>
                <button 
                    class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    onclick="hideUpgradeModal()">
                    Maybe Later
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Global variables
let currentCharts = [];
let conversationHistory = @json($conversation_history);

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    // Hide loading state and show main app
    setTimeout(() => {
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('main-app').style.display = 'flex';
    }, 1000);

    // Render existing conversation history
    renderConversationHistory();

    // Setup event listeners
    setupEventListeners();
});

function setupEventListeners() {
    // Chat form submission
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitQuery();
    });

    // Enter key in chat input
    document.getElementById('chat-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            submitQuery();
        }
    });
}

function fillPrompt(prompt) {
    document.getElementById('chat-input').value = prompt;
    document.getElementById('chat-input').focus();
}

function submitQuery() {
    const input = document.getElementById('chat-input');
    const query = input.value.trim();
    
    if (!query) return;

    // Check if limit reached
    const reportsUsed = parseInt(document.getElementById('reports-used').textContent);
    if (reportsUsed >= {{ $free_reports_limit }}) {
        showUpgradeModal();
        return;
    }

    // Add user message to chat
    addUserMessage(query);
    
    // Clear input and show loading
    input.value = '';
    setLoadingState(true);

    // Send query to backend
    fetch('{{ route("reports.insights.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ query: query })
    })
    .then(response => response.json())
    .then(data => {
        setLoadingState(false);
        
        if (data.success) {
            addAIResponse(data.response);
            updateReportsUsed(data.free_reports_used);
        } else {
            addErrorMessage(data.error);
            if (data.upgrade_required) {
                showUpgradeModal();
            }
        }
    })
    .catch(error => {
        setLoadingState(false);
        addErrorMessage('Sorry, I encountered an error. Please try again.');
        console.error('Error:', error);
    });
}

function addUserMessage(message) {
    const chatMessages = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'mb-6';
    messageDiv.innerHTML = `
        <div class="flex justify-end mb-3">
            <div class="bg-blue-600 text-white rounded-lg px-4 py-2 max-w-md">
                <p class="text-sm">${escapeHtml(message)}</p>
                <span class="text-xs opacity-75 block mt-1">${new Date().toLocaleString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' })}</span>
            </div>
        </div>
    `;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

function addAIResponse(response) {
    const chatMessages = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'mb-6';
    
    const responseContent = renderResponseContent(response.response);
    
    messageDiv.innerHTML = `
        <div class="flex justify-start">
            <div class="bg-gray-100 text-gray-900 rounded-lg px-4 py-3 max-w-2xl">
                <div class="response-content">
                    ${responseContent}
                </div>
            </div>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
    scrollToBottom();

    // Render insights in right panel
    renderInsights(response.response);
}

function addErrorMessage(error) {
    const chatMessages = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'mb-6';
    messageDiv.innerHTML = `
        <div class="flex justify-start">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 max-w-2xl">
                <p class="text-sm">${escapeHtml(error)}</p>
            </div>
        </div>
    `;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

function renderResponseContent(response) {
    if (response.type === 'text') {
        return `<p class="text-sm">${escapeHtml(response.content)}</p>`;
    } else if (response.type === 'mixed' && response.content) {
        let html = '';
        response.content.forEach(item => {
            if (item.type === 'text') {
                html += `<p class="text-sm mb-3">${escapeHtml(item.content)}</p>`;
            } else if (item.type === 'chart') {
                html += `<p class="text-sm mb-2"><strong>📊 ${item.title}</strong> - View in the insights panel →</p>`;
            } else if (item.type === 'table') {
                html += `<p class="text-sm mb-2"><strong>📋 ${item.title}</strong> - View in the insights panel →</p>`;
            }
        });
        return html;
    }
    return '<p class="text-sm">Response processed - check the insights panel for visualizations.</p>';
}

function renderInsights(response) {
    const insightsContent = document.getElementById('insights-content');
    
    if (response.type === 'text') {
        insightsContent.innerHTML = `
            <div class="p-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-medium text-blue-900 mb-2">Text Response</h3>
                    <p class="text-blue-800">${escapeHtml(response.content)}</p>
                </div>
            </div>
        `;
        return;
    }

    if (response.type === 'mixed' && response.content) {
        let html = '<div class="p-6 space-y-6">';
        
        response.content.forEach((item, index) => {
            if (item.type === 'chart') {
                const chartId = `chart-${Date.now()}-${index}`;
                html += `
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-4">${escapeHtml(item.title)}</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="${chartId}"></canvas>
                        </div>
                    </div>
                `;
                
                // Store chart data for rendering after DOM update
                setTimeout(() => {
                    renderChart(chartId, item);
                }, 100);
                
            } else if (item.type === 'table') {
                html += `
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-4">${escapeHtml(item.title)}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        ${item.headers.map(header => `<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${escapeHtml(header)}</th>`).join('')}
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    ${item.rows.map(row => `
                                        <tr>
                                            ${row.map(cell => `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(cell.toString())}</td>`).join('')}
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }
        });
        
        html += '</div>';
        insightsContent.innerHTML = html;
    }
}

function renderChart(chartId, chartData) {
    const ctx = document.getElementById(chartId);
    if (!ctx) return;

    // Clear existing chart if any
    const existingChart = Chart.getChart(chartId);
    if (existingChart) {
        existingChart.destroy();
    }

    const config = {
        type: chartData.chart_type,
        data: chartData.data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    };

    // Add specific options based on chart type
    if (chartData.chart_type === 'line') {
        config.options.scales = {
            y: {
                beginAtZero: true
            }
        };
    } else if (chartData.chart_type === 'bar') {
        config.options.scales = {
            y: {
                beginAtZero: true
            }
        };
    }

    const chart = new Chart(ctx, config);
    currentCharts.push(chart);
}

function renderConversationHistory() {
    // Render existing conversation history
    const responseContents = document.querySelectorAll('.response-content[data-response]');
    responseContents.forEach(element => {
        const response = JSON.parse(element.getAttribute('data-response'));
        element.innerHTML = renderResponseContent(response.response);
    });
}

function setLoadingState(loading) {
    const sendBtn = document.getElementById('send-btn');
    const sendText = sendBtn.querySelector('.send-text');
    const loadingText = sendBtn.querySelector('.loading-text');
    
    if (loading) {
        sendBtn.disabled = true;
        sendText.classList.add('hidden');
        loadingText.classList.remove('hidden');
    } else {
        sendBtn.disabled = false;
        sendText.classList.remove('hidden');
        loadingText.classList.add('hidden');
    }
}

function updateReportsUsed(count) {
    document.getElementById('reports-used').textContent = count;
}

function scrollToBottom() {
    const chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function clearInsights() {
    const insightsContent = document.getElementById('insights-content');
    insightsContent.innerHTML = `
        <div class="p-6 text-center text-gray-500">
            <svg class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p class="text-lg font-medium mb-2">Ready for Insights</p>
            <p class="text-sm">Charts and data tables will appear here when you ask questions.</p>
        </div>
    `;
    
    // Destroy existing charts
    currentCharts.forEach(chart => chart.destroy());
    currentCharts = [];
}

function toggleInsightsPanel() {
    const panel = document.getElementById('insights-panel');
    panel.classList.toggle('hidden');
}

function showUpgradeModal() {
    document.getElementById('upgrade-modal').classList.remove('hidden');
}

function hideUpgradeModal() {
    document.getElementById('upgrade-modal').classList.add('hidden');
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>
@endpush

@push('styles')
<style>
    /* Custom scrollbar for chat area */
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    #chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    #chat-messages::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    #chat-messages::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Smooth transitions */
    .suggested-prompt-btn {
        transition: all 0.2s ease-in-out;
    }
    
    .suggested-prompt-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Loading animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .loading-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Chart container styling */
    canvas {
        max-height: 300px !important;
    }
</style>
@endpush
