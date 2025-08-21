@extends('layouts.admin')

@section('title', 'ShuleSoft AI Assistant')

@section('page_title', 'ShuleSoft AI Assistant')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">ShuleSoft AI Assistant</li>
</ol>
@endsection

@section('content')
<div class="shulesoft-ai-interface">
  <!-- Dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

  <style>
    /* ===== SHULESOFT AI INTERFACE THEME INTEGRATION ===== */
    /* Uses admin layout theme variables for consistent theming */
    
    /* ===== BASE INTERFACE STYLING ===== */
    .shulesoft-ai-interface {
      background: var(--bg-secondary) !important;
      color: var(--text-primary) !important;
      min-height: 100vh;
      padding: 20px 0;
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .chat-header {
      background: var(--bg-primary) !important;
      color: var(--text-primary) !important;
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: var(--shadow-md);
      margin-bottom: 20px;
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .chat-header h4 {
      color: var(--text-primary) !important;
      margin: 0;
    }

    .shulesoft-ai-interface .chat-header small {
      color: var(--text-secondary) !important;
    }

    .shulesoft-ai-interface .chat-main-container {
      background: var(--bg-primary) !important;
      border-radius: 12px;
      box-shadow: var(--shadow-md);
      overflow: hidden;
      height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .chat-messages-area {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      background: var(--bg-primary) !important;
      color: var(--text-primary) !important;
      transition: all 0.3s ease;
    }

    /* ===== CHAT MESSAGE STYLING ===== */
    .shulesoft-ai-interface .chat-message {
      display: flex;
      margin-bottom: 24px;
      animation: fadeInUp 0.3s ease;
    }

    .shulesoft-ai-interface .chat-message.user {
      justify-content: flex-end;
    }

    .shulesoft-ai-interface .chat-message.ai {
      justify-content: flex-start;
    }

    .shulesoft-ai-interface .message-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      font-size: 14px;
      flex-shrink: 0;
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .chat-message.ai .message-avatar {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    [data-theme="dark"] .shulesoft-ai-interface .chat-message.ai .message-avatar {
      background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
      color: white;
    }

    .shulesoft-ai-interface .chat-message.user .message-avatar {
      background: #0d6efd;
      color: white;
      margin-right: 0;
      margin-left: 12px;
      order: 2;
    }

    .shulesoft-ai-interface .message-content {
      max-width: 70%;
      background: var(--bg-tertiary) !important;
      color: var(--text-primary) !important;
      border-radius: 18px;
      padding: 16px 20px;
      box-shadow: var(--shadow-sm);
      position: relative;
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .chat-message.user .message-content {
      background: #0d6efd !important;
      color: white !important;
      order: 1;
      border-color: #0d6efd;
    }

    .shulesoft-ai-interface .message-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
      font-size: 12px;
      color: inherit;
    }

    .shulesoft-ai-interface .chat-message.user .message-header {
      color: rgba(255,255,255,0.9) !important;
    }

    .shulesoft-ai-interface .message-time {
      color: var(--text-muted) !important;
      font-size: 11px;
    }

    .shulesoft-ai-interface .chat-message.user .message-time {
      color: rgba(255,255,255,0.7) !important;
    }

    .shulesoft-ai-interface .message-body {
      line-height: 1.5;
      color: inherit;
    }

    .shulesoft-ai-interface .message-body p {
      margin-bottom: 8px;
      color: inherit;
    }

    .shulesoft-ai-interface .message-body p:last-child {
      margin-bottom: 0;
    }

    .shulesoft-ai-interface .capability-list {
      list-style: none;
      padding: 0;
      margin: 12px 0;
    }

    .shulesoft-ai-interface .capability-list li {
      padding: 6px 0;
      display: flex;
      align-items: center;
      color: inherit;
    }

    .shulesoft-ai-interface .capability-list i {
      margin-right: 8px;
      width: 16px;
    }

    /* ===== WELCOME MESSAGE STYLING ===== */
    .shulesoft-ai-interface .welcome-message .message-content {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
      color: white !important;
      max-width: 80%;
      border-color: #667eea;
    }

    [data-theme="dark"] .shulesoft-ai-interface .welcome-message .message-content {
      background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%) !important;
      color: white !important;
    }

    .shulesoft-ai-interface .welcome-message .capability-list li {
      color: rgba(255,255,255,0.95) !important;
    }

    /* ===== AI RESPONSE COMPONENTS ===== */
    .shulesoft-ai-interface .ai-response-chart {
      margin: 16px 0;
      background: var(--bg-tertiary) !important;
      border-radius: 12px;
      padding: 16px;
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .ai-response-table {
      margin: 16px 0;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .ai-response-table table {
      background: var(--bg-primary) !important;
      color: var(--text-primary) !important;
    }

    .shulesoft-ai-interface .ai-response-table th {
      background: var(--bg-tertiary) !important;
      color: var(--text-primary) !important;
      border-color: var(--border-color);
    }

    .shulesoft-ai-interface .ai-response-table td {
      color: var(--text-primary) !important;
      border-color: var(--border-light);
    }

    .shulesoft-ai-interface .ai-response-kpis {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 12px;
      margin: 16px 0;
    }

    .shulesoft-ai-interface .kpi-card {
      background: var(--bg-tertiary) !important;
      border-radius: 12px;
      padding: 16px;
      text-align: center;
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    .shulesoft-ai-interface .kpi-value {
      font-size: 24px;
      font-weight: bold;
      color: #0d6efd;
      margin-bottom: 4px;
    }

    .shulesoft-ai-interface .kpi-label {
      font-size: 12px;
      color: var(--text-secondary) !important;
      margin-bottom: 8px;
    }

    .shulesoft-ai-interface .kpi-trend {
      font-size: 12px;
    }

    .shulesoft-ai-interface .kpi-trend.up { color: var(--success); }
    .shulesoft-ai-interface .kpi-trend.down { color: var(--danger); }
    .shulesoft-ai-interface .kpi-trend.stable { color: var(--text-secondary); }

    .shulesoft-ai-interface .recommendations {
      background: rgba(13, 110, 253, 0.1);
      border-left: 4px solid #0d6efd;
      border-radius: 8px;
      padding: 12px 16px;
      margin: 16px 0;
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .recommendations h6 {
      color: #0d6efd;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .shulesoft-ai-interface .recommendations ul {
      margin: 0;
      padding-left: 16px;
      font-size: 14px;
      color: var(--text-primary);
    }

    /* ===== CHAT INPUT AREA ===== */
    .shulesoft-ai-interface .chat-input-area {
      background: var(--bg-primary) !important;
      color: var(--text-primary) !important;
      padding: 20px;
      border-top: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .shulesoft-ai-interface .suggested-prompts-container {
      text-align: center;
    }

    .shulesoft-ai-interface .suggested-prompts {
      display: flex;
      gap: 8px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .shulesoft-ai-interface .suggestion-btn {
      background: var(--bg-tertiary) !important;
      border: 1px solid var(--border-color) !important;
      border-radius: 20px;
      padding: 8px 16px;
      font-size: 13px;
      color: var(--text-primary) !important;
      cursor: pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
    }

    .shulesoft-ai-interface .suggestion-btn:hover {
      background: #0d6efd !important;
      border-color: #0d6efd !important;
      color: white !important;
      transform: translateY(-1px);
    }

    .shulesoft-ai-interface .input-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .shulesoft-ai-interface .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      background: var(--bg-tertiary) !important;
      border: 2px solid var(--border-color);
      border-radius: 25px;
      padding: 4px;
      transition: all 0.2s ease;
    }

    .shulesoft-ai-interface .input-wrapper:focus-within {
      border-color: #0d6efd;
      box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    .shulesoft-ai-interface .chat-input {
      flex: 1;
      border: none;
      background: transparent;
      padding: 12px 16px;
      font-size: 14px;
      outline: none;
      resize: none;
      color: var(--text-primary) !important;
    }

    .shulesoft-ai-interface .chat-input::placeholder {
      color: var(--text-muted) !important;
    }

    .shulesoft-ai-interface .send-btn {
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

    .shulesoft-ai-interface .send-btn:disabled {
      background: var(--text-secondary);
      cursor: not-allowed;
    }

    .shulesoft-ai-interface .send-btn:not(:disabled):hover {
      background: #0b5ed7;
      transform: scale(1.05);
    }

    .shulesoft-ai-interface .input-footer {
      text-align: center;
      margin-top: 8px;
    }

    .shulesoft-ai-interface .input-footer small {
      color: var(--text-secondary) !important;
    }

    /* ===== TYPING INDICATOR ===== */
    .shulesoft-ai-interface .typing-indicator {
      display: flex;
      align-items: center;
      gap: 4px;
      padding: 12px 16px;
    }

    .shulesoft-ai-interface .typing-indicator span {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background-color: var(--text-secondary);
      animation: typing 1.4s infinite ease-in-out;
    }

    .shulesoft-ai-interface .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .shulesoft-ai-interface .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    /* ===== ANIMATIONS ===== */
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

    /* ===== USAGE INDICATOR & BUTTONS ===== */
    .shulesoft-ai-interface .usage-indicator .badge {
      border: 1px solid var(--border-color);
      background: var(--bg-tertiary) !important;
      color: var(--text-primary) !important;
    }

    .shulesoft-ai-interface .btn-outline-secondary {
      color: var(--text-secondary) !important;
      border-color: var(--border-color) !important;
      background: transparent !important;
    }

    .shulesoft-ai-interface .btn-outline-secondary:hover {
      background: var(--bg-tertiary) !important;
      color: var(--text-primary) !important;
      border-color: var(--border-color) !important;
    }

    .shulesoft-ai-interface .btn-primary {
      background: #0d6efd !important;
      border-color: #0d6efd !important;
      color: white !important;
    }

    .shulesoft-ai-interface .btn-primary:hover {
      background: #0b5ed7 !important;
      border-color: #0b5ed7 !important;
    }

    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 768px) {
      .shulesoft-ai-interface {
        padding: 10px 0;
      }

      .shulesoft-ai-interface .chat-header {
        margin-bottom: 10px;
        padding: 12px 15px;
      }

      .shulesoft-ai-interface .chat-main-container {
        height: calc(100vh - 150px);
      }

      .shulesoft-ai-interface .message-content {
        max-width: 85%;
      }

      .shulesoft-ai-interface .suggested-prompts {
        flex-direction: column;
        align-items: center;
      }

      .shulesoft-ai-interface .suggestion-btn {
        width: 100%;
        max-width: 300px;
      }

      .shulesoft-ai-interface .chat-header h4 {
        font-size: 1.1rem;
      }

      .shulesoft-ai-interface .chat-header .d-flex.gap-3 {
        gap: 0.5rem !important;
      }
    }

    /* ===== SCROLLBAR STYLING ===== */
    .shulesoft-ai-interface .chat-messages-area::-webkit-scrollbar {
      width: 6px;
    }

    .shulesoft-ai-interface .chat-messages-area::-webkit-scrollbar-track {
      background: var(--bg-tertiary);
    }

    .shulesoft-ai-interface .chat-messages-area::-webkit-scrollbar-thumb {
      background: var(--text-secondary);
      border-radius: 3px;
    }

    .shulesoft-ai-interface .chat-messages-area::-webkit-scrollbar-thumb:hover {
      background: var(--text-primary);
    }

    /* ===== ACCESSIBILITY ===== */
    @media (prefers-reduced-motion: reduce) {
      .shulesoft-ai-interface * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }

    /* Focus states for accessibility */
    .shulesoft-ai-interface .suggestion-btn:focus,
    .shulesoft-ai-interface .send-btn:focus,
    .shulesoft-ai-interface .chat-input:focus {
      outline: 2px solid #0d6efd;
      outline-offset: 2px;
    }
  </style>

  <!-- Main Interface -->
  <div class="container-fluid">
    <!-- Header Bar -->
    <div class="chat-header d-flex justify-content-between align-items-center mb-3">
      <div>
        <h4 class="mb-0">
          <i class="fas fa-brain text-primary me-2"></i>
          ShuleSoft AI Assistant
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

  <!-- JavaScript -->
  <script>
    let conversationHistory = [];
    let chartInstances = [];

    // Enhanced message handling for inline content
    function addMessage(content, sender, responseData = null) {
      const messageId = 'msg-' + Date.now();
      const timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
      let messageHtml = '';
      
      if (sender === 'user') {
        messageHtml = `
          <div class="chat-message user" id="${messageId}">
            <div class="message-avatar">
              <i class="fas fa-user"></i>
            </div>
            <div class="message-content">
              <div class="message-header">
                <strong>You</strong>
                <span class="message-time">${timestamp}</span>
              </div>
              <div class="message-body">
                <p>${content}</p>
              </div>
            </div>
          </div>
        `;
      } else {
        // AI message with inline visualizations
        let visualizationContent = '';
        
        if (responseData) {
          if (responseData.type === 'chart' && responseData.data.charts) {
            visualizationContent += renderInlineChart(responseData.data.charts[0], messageId);
          } else if (responseData.type === 'table' && responseData.data.tables) {
            visualizationContent += renderInlineTable(responseData.data.tables[0]);
          } else if (responseData.type === 'kpi' && responseData.data.kpis) {
            visualizationContent += renderInlineKPIs(responseData.data.kpis);
          }
          
          if (responseData.recommendations && responseData.recommendations.length > 0) {
            visualizationContent += `
              <div class="recommendations">
                <h6><i class="fas fa-lightbulb me-1"></i>Recommendations</h6>
                <ul>
                  ${responseData.recommendations.map(rec => `<li>${rec}</li>`).join('')}
                </ul>
              </div>
            `;
          }
        }
        
        messageHtml = `
          <div class="chat-message ai" id="${messageId}">
            <div class="message-avatar">
              <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">
              <div class="message-header">
                <strong>ShuleSoft AI</strong>
                <span class="message-time">${timestamp}</span>
              </div>
              <div class="message-body">
                <p>${content}</p>
                ${visualizationContent}
              </div>
            </div>
          </div>
        `;
      }
      
      $('#chat-container').append(messageHtml);
      $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);
      
      // Initialize any charts that were added
      if (responseData && responseData.type === 'chart') {
        setTimeout(() => initializeChart(messageId, responseData.data.charts[0]), 100);
      }
      
      // Hide suggestions after first user message
      if (sender === 'user') {
        $('#suggestions-container').fadeOut();
      }
    }

    function renderInlineChart(chartData, messageId) {
      const chartId = `chart-${messageId}`;
      return `
        <div class="ai-response-chart">
          <canvas id="${chartId}" style="max-height: 300px;"></canvas>
        </div>
      `;
    }

    function renderInlineTable(tableData) {
      let tableHtml = '<div class="ai-response-table"><table class="table table-sm mb-0">';
      
      if (tableData.headers) {
        tableHtml += '<thead class="table-light"><tr>';
        tableData.headers.forEach(header => {
          tableHtml += `<th style="font-size: 12px;">${header}</th>`;
        });
        tableHtml += '</tr></thead>';
      }
      
      tableHtml += '<tbody>';
      if (tableData.rows) {
        tableData.rows.forEach(row => {
          tableHtml += '<tr>';
          row.forEach(cell => {
            tableHtml += `<td style="font-size: 12px;">${cell}</td>`;
          });
          tableHtml += '</tr>';
        });
      }
      tableHtml += '</tbody></table></div>';
      
      return tableHtml;
    }

    function renderInlineKPIs(kpis) {
      let kpiHtml = '<div class="ai-response-kpis">';
      
      kpis.forEach(kpi => {
        const trendClass = kpi.trend === 'up' ? 'up' : 
                          kpi.trend === 'down' ? 'down' : 'stable';
        const trendIcon = kpi.trend === 'up' ? 'fa-arrow-up' : 
                         kpi.trend === 'down' ? 'fa-arrow-down' : 'fa-minus';
        
        kpiHtml += `
          <div class="kpi-card">
            <div class="kpi-value">${kpi.value}</div>
            <div class="kpi-label">${kpi.label}</div>
            <div class="kpi-trend ${trendClass}">
              <i class="fas ${trendIcon}"></i>
            </div>
          </div>
        `;
      });
      
      kpiHtml += '</div>';
      return kpiHtml;
    }

    function initializeChart(messageId, chartData) {
      const chartId = `chart-${messageId}`;
      const canvas = document.getElementById(chartId);
      
      if (!canvas) return;
      
      const ctx = canvas.getContext('2d');
      
      const chart = new Chart(ctx, {
        type: chartData.type || 'bar',
        data: chartData.data || {
          labels: ['Sample'],
          datasets: [{
            label: 'Data',
            data: [100],
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1']
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: chartData.title || 'AI Generated Chart'
            },
            legend: {
              display: true,
              position: 'bottom'
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
      
      chartInstances.push(chart);
    }

    function sendMessage() {
      const input = $('#chat-input').val().trim();
      if (!input) return;
      
      addMessage(input, 'user');
      $('#chat-input').val('');
      updateSendButton();
      
      // Show typing indicator
      const typingId = 'typing-' + Date.now();
      const timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
      const typingHtml = `
        <div class="chat-message ai" id="${typingId}">
          <div class="message-avatar">
            <i class="fas fa-robot"></i>
          </div>
          <div class="message-content">
            <div class="message-header">
              <strong>ShuleSoft AI</strong>
              <span class="message-time">${timestamp}</span>
            </div>
            <div class="typing-indicator">
              <span></span><span></span><span></span>
            </div>
          </div>
        </div>
      `;
      $('#chat-container').append(typingHtml);
      $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);

      // Send AJAX request
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
          $('#' + typingId).remove();
          
          if (response.success) {
            const aiResponse = response.response;
            let displayText = '';
            
            if (aiResponse.data && aiResponse.data.summary) {
              displayText = aiResponse.data.summary;
              if (aiResponse.data.details && aiResponse.data.details !== aiResponse.data.summary) {
                displayText += ' ' + aiResponse.data.details;
              }
            } else {
              displayText = 'Analysis complete. The results are displayed above.';
            }
            
            addMessage(displayText, 'ai', aiResponse);
            
            // Update conversation history
            conversationHistory.push(
              {role: 'user', content: input},
              {role: 'assistant', content: displayText}
            );
            
            // Keep only last 6 messages for context
            if (conversationHistory.length > 6) {
              conversationHistory = conversationHistory.slice(-6);
            }
            
            // Update usage counter
            updateUsageCounter();
            
          } else {
            let errorMessage = "Sorry, I couldn't process your request. Please try again.";
            
            if (response.requires_upgrade) {
              errorMessage = response.message;
              setTimeout(() => {
                $('#upgradeModal').modal('show');
              }, 1000);
            }
            
            addMessage(errorMessage, 'ai');
          }
        },
        error: function (xhr) {
          $('#' + typingId).remove();
          
          let errorMessage = "I'm experiencing technical difficulties. Please try again.";
          
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

    function updateSendButton() {
      const hasText = $('#chat-input').val().trim().length > 0;
      $('#send-btn').prop('disabled', !hasText);
    }

    function updateUsageCounter() {
      const current = parseInt($('#remaining-reports').text()) - 1;
      $('#remaining-reports').text(Math.max(0, current));
      
      if (current <= 0) {
        $('#remaining-reports').parent().removeClass('bg-light text-dark')
                                      .addClass('bg-warning text-dark');
      }
    }

    // Event Listeners
    $('#send-btn').on('click', sendMessage);

    $('#chat-input').on('keypress', function(e) {
      if (e.which === 13 && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });

    $('#chat-input').on('input', updateSendButton);

    $('.suggestion-btn').on('click', function () {
      const prompt = $(this).data('prompt');
      $('#chat-input').val(prompt);
      updateSendButton();
      sendMessage();
    });

    $('#clear-chat-btn').on('click', function() {
      if (confirm('Are you sure you want to clear the chat history?')) {
        $('#chat-container').empty();
        conversationHistory = [];
        $('#suggestions-container').fadeIn();
        
        // Destroy all chart instances
        chartInstances.forEach(chart => chart.destroy());
        chartInstances = [];
        
        // Re-add welcome message
        const welcomeHtml = `
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
        `;
        $('#chat-container').append(welcomeHtml);
      }
    });

    // Initialize
    updateSendButton();
  </script>

  <!-- Upgrade Modal -->
  <div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 id="upgradeModalLabel" class="modal-title">
            <i class="fas fa-crown me-2"></i>Upgrade to Premium
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h4>Unlock Unlimited AI Reports</h4>
            <p class="text-muted">Get unlimited access to ShuleSoft AI insights and advanced analytics</p>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="card border">
                <div class="card-body text-center">
                  <h6 class="card-title">Free Tier</h6>
                  <ul class="list-unstyled small">
                    <li><i class="fas fa-check text-success me-1"></i> 3 reports per month</li>
                    <li><i class="fas fa-check text-success me-1"></i> Basic insights</li>
                    <li><i class="fas fa-check text-success me-1"></i> Standard support</li>
                  </ul>
                  <div class="h4 text-muted">Free</div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-primary">
                <div class="card-header bg-primary text-white text-center">
                  <h6 class="mb-0">Premium</h6>
                </div>
                <div class="card-body text-center">
                  <ul class="list-unstyled small">
                    <li><i class="fas fa-check text-success me-1"></i> Unlimited reports</li>
                    <li><i class="fas fa-check text-success me-1"></i> Advanced analytics</li>
                    <li><i class="fas fa-check text-success me-1"></i> Export functionality</li>
                    <li><i class="fas fa-check text-success me-1"></i> Priority support</li>
                  </ul>
                  <div class="h4 text-primary">TSh 50,000</div>
                  <small class="text-muted">per school/month</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Maybe Later</button>
          <button type="button" class="btn btn-primary">
            <i class="fas fa-crown me-1"></i>Upgrade Now
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
