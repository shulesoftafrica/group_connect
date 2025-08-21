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
    /* ===== DARK MODE COMPLETE OVERRIDES ===== */
    [data-theme="dark"] {
        --bg-primary: #121212;
        --bg-secondary: #1e1e1e;
        --bg-tertiary: #2a2a2a;
        --text-primary: #ffffff;
        --text-secondary: #b3b3b3;
        --border-color: #333333;
        --chat-bg-primary: #1e1e1e;
        --chat-bg-tertiary: #2a2a2a;
        --chat-text-primary: #ffffff;
        --chat-text-secondary: #b3b3b3;
        --chat-border-color: #333333;
        --chat-user-bg: #0d6efd;
        --chat-gradient: linear-gradient(135deg, #4a5568, #2d3748);
    }

    [data-theme="dark"] html,
    [data-theme="dark"] body,
    [data-theme="dark"] .ai-reports-master-page,
    [data-theme="dark"] .chat-interface,
    [data-theme="dark"] .container-fluid,
    [data-theme="dark"] .chat-main-container,
    [data-theme="dark"] .modal-content,
    [data-theme="dark"] .card,
    [data-theme="dark"] .chat-messages-area,
    [data-theme="dark"] .chat-input-area {
        background: var(--bg-secondary) !important;
        color: var(--text-primary) !important;
    }

    [data-theme="dark"] table,
    [data-theme="dark"] th,
    [data-theme="dark"] td,
    [data-theme="dark"] tr {
        background: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .chat-header,
    [data-theme="dark"] .chat-main-container,
    [data-theme="dark"] .message-content,
    [data-theme="dark"] .input-wrapper,
    [data-theme="dark"] .suggestion-btn {
        background: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        border: 1px solid var(--border-color) !important;
    }

    [data-theme="dark"] .chat-message.user .message-content {
        background: var(--chat-user-bg) !important;
        color: #fff !important;
    }

    [data-theme="dark"] .welcome-message .message-content {
        background: var(--chat-gradient) !important;
        color: #fff !important;
    }

    [data-theme="dark"] .btn,
    [data-theme="dark"] .badge,
    [data-theme="dark"] .send-btn {
        background: transparent !important;
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .btn-outline-secondary:hover,
    [data-theme="dark"] .suggestion-btn:hover {
        background: var(--bg-tertiary) !important;
        color: var(--text-primary) !important;
    }

    [data-theme="dark"] ::placeholder,
    [data-theme="dark"] .text-muted {
        color: var(--text-secondary) !important;
    }

    [data-theme="dark"] .modal-content,
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-footer {
        background: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .chat-messages-area::-webkit-scrollbar-track {
        background: var(--bg-tertiary) !important;
    }

    [data-theme="dark"] .chat-messages-area::-webkit-scrollbar-thumb {
        background: var(--text-secondary) !important;
    }

    [data-theme="dark"] div,
    [data-theme="dark"] section,
    [data-theme="dark"] header,
    [data-theme="dark"] footer,
    [data-theme="dark"] main,
    [data-theme="dark"] aside,
    [data-theme="dark"] nav {
        background: transparent !important;
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
    }

    @media (prefers-reduced-motion: reduce) {
        * {
            transition-duration: 0.01ms !important;
            animation-duration: 0.01ms !important;
        }
    }

    [data-theme="dark"] body * {
        background-color: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
    }
</style>

/* Keep original light-mode styles intact outside [data-theme="dark"] */
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
                <strong>ShuleSoft AI</strong>
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

        if (Array.isArray(tableData) && tableData.length > 0) {
            // simple HTML escaper to avoid injection and handle null/undefined
            const escapeHtml = (unsafe) => {
                if (unsafe === null || unsafe === undefined) return '';
                return String(unsafe)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            // Detect whether rows are plain arrays or objects
            const isArrayOfArrays = Array.isArray(tableData[0]);

            // Add headers
            tableHtml += '<thead><tr>';
            if (isArrayOfArrays) {
                // generic column headers for array rows
                tableData[0].forEach((_, idx) => {
                    tableHtml += `<th>Column ${idx + 1}</th>`;
                });
            } else {
                Object.keys(tableData[0]).forEach(header => {
                    tableHtml += `<th>${escapeHtml(header)}</th>`;
                });
            }
            tableHtml += '</tr></thead>';

            // Add rows
            tableHtml += '<tbody>';
            tableData.forEach(row => {
                tableHtml += '<tr>';
                if (isArrayOfArrays && Array.isArray(row)) {
                    row.forEach(cell => {
                        tableHtml += `<td>${escapeHtml(cell)}</td>`;
                    });
                } else if (row && typeof row === 'object') {
                    Object.values(row).forEach(cell => {
                        tableHtml += `<td>${escapeHtml(cell)}</td>`;
                    });
                } else {
                    // fallback for unexpected row types
                    tableHtml += `<td>${escapeHtml(row)}</td>`;
                }
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

@endsection