@extends('layouts.admin')

@section('title', 'AI-Powered Reports & Insights')

@section('page_title', 'AI Reports & Insights')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">AI Reports & Insights</li>
</ol>
@endsection

@section('content')
<div class="ai-reports-master-page container-fluid py-4">
    <!-- Top Row: Usage + Actions -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-8 d-flex gap-3 align-items-center">
            <div class="me-2">
                <h4 class="mb-0">AI Reports & Insights</h4>
                <small class="text-muted">Ask questions in plain language — we'll analyze your school's data and generate charts, tables, and recommendations.</small>
            </div>
        </div>

        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="me-3">
                <span class="small text-muted me-1">Free reports left</span>
                <span id="remaining-reports" class="badge bg-primary">3</span>
            </span>
            <button class="btn btn-outline-secondary btn-sm" id="clear-convo-btn" title="Clear conversation">Clear</button>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal" title="Upgrade for unlimited reports">
                Upgrade
            </button>
        </div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

  <style>
    /* ChatGPT-style Interface Styling */
    .chat-interface {
      background: #f8f9fa;
      min-height: 100vh;
      padding: 20px 0;
    }

    .chat-header {
      background: white;
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .chat-main-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }

    .chat-messages-area {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      background: #f8f9fa;
    }

    .chat-message {
      display: flex;
      margin-bottom: 24px;
      animation: fadeInUp 0.3s ease;
    }

    .chat-message.user {
      justify-content: flex-end;
    }

    .chat-message.ai {
      justify-content: flex-start;
    }

    .message-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      font-size: 14px;
    }

    .chat-message.ai .message-avatar {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .chat-message.user .message-avatar {
      background: #0d6efd;
      color: white;
      margin-right: 0;
      margin-left: 12px;
      order: 2;
    }

    .message-content {
      max-width: 70%;
      background: white;
      border-radius: 18px;
      padding: 16px 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.08);
      position: relative;
    }

    .chat-message.user .message-content {
      background: #0d6efd;
      color: white;
      order: 1;
    }

    .message-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
      font-size: 12px;
    }

    .chat-message.user .message-header {
      color: rgba(255,255,255,0.9);
    }

    .message-time {
      color: #6c757d;
      font-size: 11px;
    }

    .chat-message.user .message-time {
      color: rgba(255,255,255,0.7);
    }

    .message-body {
      line-height: 1.5;
    }

    .message-body p {
      margin-bottom: 8px;
    }

    .message-body p:last-child {
      margin-bottom: 0;
    }

    .capability-list {
      list-style: none;
      padding: 0;
      margin: 12px 0;
    }

    .capability-list li {
      padding: 6px 0;
      display: flex;
      align-items: center;
    }

    .capability-list i {
      margin-right: 8px;
      width: 16px;
    }

    /* Welcome Message Special Styling */
    .welcome-message .message-content {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      max-width: 80%;
    }

    .welcome-message .capability-list li {
      color: rgba(255,255,255,0.95);
    }

    /* AI Response Components */
    .ai-response-chart {
      margin: 16px 0;
      background: #f8f9fa;
      border-radius: 12px;
      padding: 16px;
      border: 1px solid #e9ecef;
    }

    .ai-response-table {
      margin: 16px 0;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid #e9ecef;
    }

    .ai-response-kpis {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 12px;
      margin: 16px 0;
    }

    .kpi-card {
      background: #f8f9fa;
      border-radius: 12px;
      padding: 16px;
      text-align: center;
      border: 1px solid #e9ecef;
      transition: transform 0.2s ease;
    }

    .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .kpi-value {
      font-size: 24px;
      font-weight: bold;
      color: #0d6efd;
      margin-bottom: 4px;
    }

    .kpi-label {
      font-size: 12px;
      color: #6c757d;
      margin-bottom: 8px;
    }

    .kpi-trend {
      font-size: 12px;
    }

    .kpi-trend.up { color: #198754; }
    .kpi-trend.down { color: #dc3545; }
    .kpi-trend.stable { color: #6c757d; }

    .recommendations {
      background: rgba(13, 110, 253, 0.1);
      border-left: 4px solid #0d6efd;
      border-radius: 8px;
      padding: 12px 16px;
      margin: 16px 0;
    }

    .recommendations h6 {
      color: #0d6efd;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .recommendations ul {
      margin: 0;
      padding-left: 16px;
      font-size: 14px;
    }

    /* Chat Input Area */
    .chat-input-area {
      background: white;
      padding: 20px;
      border-top: 1px solid #e9ecef;
    }

    .suggested-prompts-container {
      text-align: center;
    }

    .suggested-prompts {
      display: flex;
      gap: 8px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .suggestion-btn {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 20px;
      padding: 8px 16px;
      font-size: 13px;
      color: #495057;
      cursor: pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
    }

    .suggestion-btn:hover {
      background: #e9ecef;
      border-color: #0d6efd;
      color: #0d6efd;
      transform: translateY(-1px);
    }

    .input-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      background: #f8f9fa;
      border: 2px solid #e9ecef;
      border-radius: 25px;
      padding: 4px;
      transition: border-color 0.2s ease;
    }

    .input-wrapper:focus-within {
      border-color: #0d6efd;
      box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    .chat-input {
      flex: 1;
      border: none;
      background: transparent;
      padding: 12px 16px;
      font-size: 14px;
      outline: none;
      resize: none;
    }

    .send-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: none;
      background: #0d6efd;
      color: white;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .send-btn:disabled {
      background: #6c757d;
      cursor: not-allowed;
    }

    .send-btn:not(:disabled):hover {
      background: #0b5ed7;
      transform: scale(1.05);
    }

    .input-footer {
      text-align: center;
      margin-top: 8px;
    }

    /* Typing Indicator */
    .typing-indicator {
      display: flex;
      align-items: center;
      gap: 4px;
      padding: 12px 16px;
    }

    .typing-indicator span {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background-color: #6c757d;
      animation: typing 1.4s infinite ease-in-out;
    }

    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
      0%, 60%, 100% {
        transform: scale(1);
        opacity: 0.5;
      }
      30% {
        transform: scale(1.2);
        opacity: 1;
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Usage Indicator */
    .usage-indicator .badge {
      border: 1px solid #dee2e6;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .chat-interface {
        padding: 10px 0;
      }

      .chat-header {
        margin-bottom: 10px;
        padding: 12px 15px;
      }

      .chat-main-container {
        height: calc(100vh - 150px);
      }

      .message-content {
        max-width: 85%;
      }

      .suggested-prompts {
        flex-direction: column;
        align-items: center;
      }

      .suggestion-btn {
        width: 100%;
        max-width: 300px;
      }

      .chat-header h4 {
        font-size: 1.1rem;
      }

      .chat-header .d-flex.gap-3 {
        gap: 0.5rem !important;
      }
    }

    /* Scrollbar Styling */
    .chat-messages-area::-webkit-scrollbar {
      width: 6px;
    }

    .chat-messages-area::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .chat-messages-area::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 3px;
    }

    .chat-messages-area::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
  </style>


  <!-- ChatGPT-style Interface -->
  <div class="chat-interface">
    <div class="container-fluid">
      <!-- Header Bar -->
      <div class="chat-header d-flex justify-content-between align-items-center mb-3">
        <div>
          <h4 class="mb-0">
            <i class="fas fa-brain text-primary me-2"></i>
            ShuleSoftAI Assistant
          </h4>
          <small class="text-muted">Your intelligent school data analyst</small>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div class="usage-indicator">
            <span class="badge bg-light text-dark">
              <i class="fas fa-chart-line me-1"></i>
              <span id="remaining-reports">3</span> reports left
            </span>
          </div>
          <button class="btn btn-outline-secondary btn-sm" id="clear-chat-btn">
            <i class="fas fa-trash me-1"></i>Clear Chat
          </button>
          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal">
            <i class="fas fa-crown me-1"></i>Upgrade
          </button>
        </div>
      </div>

      <!-- Main Chat Container -->
      <div class="chat-main-container">
        <!-- Chat Messages Area -->
        <div class="chat-messages-area" id="chat-container">
          <!-- Welcome Message -->
          <div class="chat-message ai welcome-message">
            <div class="message-avatar">
              <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">
              <div class="message-header">
                <strong>ShuleSoftAI</strong>
                <span class="message-time">Just now</span>
              </div>
              <div class="message-body">
                <p>Hello! I'm your AI assistant for school data analysis. I can help you with:</p>
                <ul class="capability-list">
                  <li><i class="fas fa-chart-line text-success"></i> Revenue and financial analysis</li>
                  <li><i class="fas fa-users text-primary"></i> Student enrollment trends</li>
                  <li><i class="fas fa-money-bill text-warning"></i> Payment collection insights</li>
                  <li><i class="fas fa-calendar-check text-info"></i> Attendance reporting</li>
                  <li><i class="fas fa-exclamation-triangle text-danger"></i> Fee arrears analysis</li>
                </ul>
                <p class="mb-0">What would you like to analyze today?</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
          <!-- Suggested Prompts -->
          <div class="suggested-prompts-container mb-3" id="suggestions-container">
            <div class="suggested-prompts">
              <button class="suggestion-btn" data-prompt="Show me total revenue this month">
                <i class="fas fa-chart-bar me-2"></i>Monthly Revenue
              </button>
              <button class="suggestion-btn" data-prompt="How many students are enrolled this year?">
                <i class="fas fa-users me-2"></i>Student Enrollment
              </button>
              <button class="suggestion-btn" data-prompt="What are our outstanding fee arrears?">
                <i class="fas fa-exclamation-circle me-2"></i>Fee Arrears
              </button>
              <button class="suggestion-btn" data-prompt="Show payment collection trends">
                <i class="fas fa-money-bill-wave me-2"></i>Payment Trends
              </button>
            </div>
          </div>

          <!-- Input Box -->
          <div class="input-container">
            <div class="input-wrapper">
              <input 
                type="text" 
                id="chat-input" 
                class="chat-input" 
                placeholder="Ask me anything about your school data..."
                autocomplete="off"
              >
              <button class="send-btn" id="send-btn" disabled>
                <i class="fas fa-paper-plane"></i>
              </button>
            </div>
            <div class="input-footer">
              <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Your data is secure and processed locally
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
    let conversationHistory = [];
    let chartInstance = null;

    // Chat handling with enhanced AI response processing
    function addMessage(content, sender, responseData = null) {
        const messageId = 'msg-' + Date.now();
        let messageHtml = '';

        if (sender === 'user') {
            messageHtml = `
                <div class="chat-message user" id="${messageId}">
                    <div class="chat-bubble">${content}</div>
                </div>
            `;
        } else {
            messageHtml = `
                <div class="chat-message ai" id="${messageId}">
                    <div class="chat-bubble">
                        <div class="ai-response">
                            <div class="response-content">${content}</div>
                            ${responseData && responseData.recommendations ? 
                                `<div class="recommendations mt-2">
                                    <small class="text-muted">💡 Recommendations:</small>
                                    <ul class="small mt-1">
                                        ${responseData.recommendations.map(rec => `<li>${rec}</li>`).join('')}
                                    </ul>
                                </div>` : ''
                            }
                        </div>
                    </div>
                </div>
            `;
        }

        $('#chat-container').append(messageHtml);
        $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);
    }

    function updateVisualizationPanel(response) {
        const panel = $('#visualization-panel');
        panel.empty();

        if (response.type === 'chart' && response.data.charts) {
            renderChart(response.data.charts[0]);
        } else if (response.type === 'table' && response.data.tables) {
            renderTable(response.data.tables);
        } else if (response.type === 'kpi' && response.data.kpis) {
            renderKPIs(response.data.kpis);
        } else {
            // Default text visualization
            panel.html(`
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-brain fa-2x text-primary"></i>
                    </div>
                    <h6 class="mb-2">Analysis Complete</h6>
                    <p class="text-muted small">${response.data.summary || 'AI analysis processed successfully.'}</p>
                </div>
            `);
        }

        $('#download-btn').prop('disabled', false);
    }

    function renderChart(chartData) {
        const panel = $('#visualization-panel');
        panel.html('<canvas id="aiChart" width="400" height="200"></canvas>');

        const ctx = document.getElementById('aiChart').getContext('2d');

        // Destroy existing chart if it exists
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: chartData.type || 'bar',
            data: chartData.data || {
                labels: ['Sample'],
                datasets: [{
                    label: 'Data',
                    data: [100],
                    backgroundColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: chartData.title || 'AI Generated Chart'
                    }
                }
            }
        });
    }

    function renderTable(tableData) {
        const panel = $('#visualization-panel');
        let tableHtml = '<div class="table-responsive"><table class="table table-sm">';

        if (tableData.length > 0) {
            // Add headers
            tableHtml += '<thead><tr>';
            Object.keys(tableData[0]).forEach(header => {
                tableHtml += `<th>${header}</th>`;
            });
            tableHtml += '</tr></thead>';

            // Add rows
            tableHtml += '<tbody>';
            tableData.forEach(row => {
                tableHtml += '<tr>';
                Object.values(row).forEach(cell => {
                    tableHtml += `<td>${cell}</td>`;
                });
                tableHtml += '</tr>';
            });
            tableHtml += '</tbody>';
        }

        tableHtml += '</table></div>';
        panel.html(tableHtml);
    }

    function renderKPIs(kpis) {
        const panel = $('#visualization-panel');
        let kpiHtml = '<div class="row g-3">';

        kpis.forEach(kpi => {
            const trendIcon = kpi.trend === 'up' ? 'fa-arrow-up text-success' : 
                             kpi.trend === 'down' ? 'fa-arrow-down text-danger' : 
                             'fa-minus text-muted';

            kpiHtml += `
                <div class="col-md-4">
                    <div class="p-3 border rounded text-center">
                        <div class="h4 mb-1">${kpi.value}</div>
                        <div class="text-muted small">${kpi.label}</div>
                        <div class="mt-1"><i class="fas ${trendIcon}"></i></div>
                    </div>
                </div>
            `;
        });

        kpiHtml += '</div>';
        panel.html(kpiHtml);
    }

    $('#send-btn').on('click', function () {
        const input = $('#chat-input').val().trim();
        if (input) {
            addMessage(input, 'user');
            $('#chat-input').val('');

            // Show typing indicator
            const typingId = 'typing-' + Date.now();
            const typingHtml = `
                <div class="chat-message ai" id="${typingId}">
                    <div class="chat-bubble">
                        <div class="typing-indicator">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
            `;
            $('#chat-container').append(typingHtml);
            $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);

            // Send data to the backend via AJAX
            $.ajax({
                url: '{{ route("insights.ai-query") }}',
                method: 'POST',
                data: {
                    query: input,
                    conversation_history: conversationHistory
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // Remove typing indicator
                    $('#' + typingId).remove();

                    if (response.success) {
                        const aiResponse = response.response;
                        let displayText = '';

                        // Handle different response types
                        if (aiResponse.data && aiResponse.data.summary) {
                            displayText = aiResponse.data.summary;
                            if (aiResponse.data.details && aiResponse.data.details !== aiResponse.data.summary) {
                                displayText += '\n\n' + aiResponse.data.details;
                            }
                        } else {
                            displayText = 'Analysis complete. Check the visualization panel for details.';
                        }

                        addMessage(displayText, 'ai', aiResponse);

                        // Update visualization panel
                        updateVisualizationPanel(aiResponse);

                        // Update conversation history
                        conversationHistory.push(
                            {role: 'user', content: input},
                            {role: 'assistant', content: displayText}
                        );

                        // Keep only last 6 messages (3 exchanges) to manage context
                        if (conversationHistory.length > 6) {
                            conversationHistory = conversationHistory.slice(-6);
                        }

                        console.log('AI Response:', aiResponse);
                    } else {
                        let errorMessage = "Sorry, I couldn't process your request.";

                        if (response.requires_upgrade) {
                            errorMessage = response.message;
                            // Auto-show upgrade modal
                            setTimeout(() => {
                                $('#upgradeModal').modal('show');
                            }, 1000);
                        }

                        addMessage(errorMessage, 'ai');
                    }
                },
                error: function (xhr) {
                    // Remove typing indicator
                    $('#' + typingId).remove();

                    let errorMessage = "An error occurred while processing your request.";

                    if (xhr.status === 429) {
                        errorMessage = "You've reached your free tier limit. Upgrade for unlimited reports!";
                        setTimeout(() => {
                            $('#upgradeModal').modal('show');
                        }, 1000);
                    }

                    addMessage(errorMessage, 'ai');
                }
            });
        }
    });

    // Enter key support
    $('#chat-input').on('keypress', function(e) {
        if (e.which === 13) {
            $('#send-btn').click();
        }
    });

    $('.suggested-prompts button').on('click', function () {
        const prompt = $(this).text();
        $('#chat-input').val(prompt);
        $('#send-btn').click();
    });

    // Download functionality
    $('#download-btn').on('click', function() {
        if (chartInstance) {
            const link = document.createElement('a');
            link.download = 'ai-chart.png';
            link.href = chartInstance.toBase64Image();
            link.click();
        } else {
            // For tables or other content
            const panel = document.getElementById('visualization-panel');
            html2canvas(panel).then(canvas => {
                const link = document.createElement('a');
                link.download = 'ai-insight.png';
                link.href = canvas.toDataURL();
                link.click();
            }).catch(() => {
                alert('Download feature requires a visualization to be displayed.');
            });
        }
    });
</script>
  </script>


    <!-- Simple accessibility & guidance modal -->
    <div class="modal fade" id="howToModal" tabindex="-1" aria-labelledby="howToLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="howToLabel" class="modal-title">How to use the AI Assistant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li>Type a natural question about your schools (example: "Show revenue for School A vs School B this year").</li>
                        <li>Pick a suggested prompt to get a quick start.</li>
                        <li>If a chart or table appears, you can export or ask follow-up questions.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upgrade Modal (kept as in original but simplified content) -->
    <div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="upgradeModalLabel" class="modal-title"><i class="fas fa-crown me-2"></i>Upgrade to Premium</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Unlimited AI reports, advanced visuals, export options and priority processing — Tsh 50,000 per school/month.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <h6>Free</h6>
                                <ul class="small">
                                    <li>3 reports / month</li>
                                    <li>Basic visuals</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded bg-primary text-white">
                                <h6>Premium</h6>
                                <ul class="small">
                                    <li>Unlimited reports</li>
                                    <li>PDF / Excel export</li>
                                    <li>Priority processing</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Not now</button>
                    <button class="btn btn-primary btn-sm">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js (used when rendering charts) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
</script>

@endsection

@section('styles')
<style>
/* Clean, accessible visuals */
.ai-reports-master-page { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; color:#212529; }
.card { border: none; border-radius: .75rem; }
.message-content { line-height:1.4; }
.ai-avatar { background: linear-gradient(135deg,#0d6efd,#0056b3); color:#fff; }
.user-avatar { background: linear-gradient(135deg,#20c997,#138f66); color:#fff; }
.suggested-prompt { cursor: pointer; border-radius: 999px; padding: .35rem .7rem; font-size: .85rem; }
.empty-state i { color: #6c757d; }
.badge { font-weight:600; padding:.5em .6em; }
@media (max-width: 991px) {
    .ai-reports-master-page .col-lg-6 { margin-bottom: 1rem; }
}
</style>
@endsection
