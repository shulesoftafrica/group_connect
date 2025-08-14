@extends('layouts.admin')

@section('title', 'Bank Reconciliation')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">Finance</a></li>
                    <li class="breadcrumb-item active">Bank Reconciliation</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">Bank Reconciliation</h1>
            <p class="text-muted mb-0">Reconcile bank statements with accounting records</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importStatementModal">
                <i class="bi bi-upload me-1"></i> Import Statement
            </button>
            <button class="btn btn-outline-success" onclick="exportReconciliationReport()">
                <i class="bi bi-download me-1"></i> Export Report
            </button>
            <button class="btn btn-primary" onclick="autoReconcile()">
                <i class="bi bi-check2-all me-1"></i> Auto Reconcile
            </button>
        </div>
    </div>

    <!-- Reconciliation Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-primary">{{ $summary['total_accounts'] }}</div>
                    <div class="text-muted">Total Accounts</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-success">{{ $summary['reconciled_accounts'] }}</div>
                    <div class="text-muted">Reconciled</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-warning">{{ $summary['pending_reconciliation'] }}</div>
                    <div class="text-muted">Pending</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-danger">TZS {{ number_format($summary['total_discrepancies']) }}</div>
                    <div class="text-muted">Discrepancies</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Accounts Selection -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-bank me-2"></i>Select Bank Account
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">School</label>
                    <select class="form-select" id="schoolSelect" onchange="loadSchoolAccounts()">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->settings['school_name'] ?? 'Unknown School' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bank Account</label>
                    <select class="form-select" id="accountSelect" onchange="loadReconciliationData()">
                        <option value="">Select Account</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Reconciliation Panel -->
    <div id="reconciliationPanel" style="display: none;">
        <!-- Account Summary -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-info-circle me-2"></i>Account Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="text-muted small">Bank Balance</label>
                        <div class="h5 text-primary" id="bankBalance">TZS 0</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Book Balance</label>
                        <div class="h5 text-info" id="bookBalance">TZS 0</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Difference</label>
                        <div class="h5 text-danger" id="balanceDifference">TZS 0</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Reconciliation Status</label>
                        <div id="reconciliationStatus">
                            <span class="badge bg-warning">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outstanding Items -->
        <div class="row mb-4">
            <!-- Outstanding Deposits -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="bi bi-plus-circle me-2"></i>Outstanding Deposits
                            <span class="badge bg-success ms-2" id="outstandingDepositsCount">0</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm" id="outstandingDepositsTable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAllDeposits" onchange="toggleAllDeposits()"></th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic content -->
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-2">
                            <strong>Total: TZS <span id="totalOutstandingDeposits">0</span></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Outstanding Withdrawals -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">
                            <i class="bi bi-dash-circle me-2"></i>Outstanding Withdrawals
                            <span class="badge bg-danger ms-2" id="outstandingWithdrawalsCount">0</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm" id="outstandingWithdrawalsTable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAllWithdrawals" onchange="toggleAllWithdrawals()"></th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic content -->
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-2">
                            <strong>Total: TZS <span id="totalOutstandingWithdrawals">0</span></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Statement vs Book Records -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-list-check me-2"></i>Transaction Matching
                </h6>
                <div>
                    <button class="btn btn-sm btn-outline-primary" onclick="matchSimilar()">
                        <i class="bi bi-search me-1"></i> Match Similar
                    </button>
                    <button class="btn btn-sm btn-outline-success" onclick="matchSelected()">
                        <i class="bi bi-check me-1"></i> Match Selected
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Bank Statement -->
                    <div class="col-lg-6">
                        <h6 class="font-weight-bold text-primary mb-3">Bank Statement</h6>
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover table-sm">
                                <thead class="sticky-top bg-light">
                                    <tr>
                                        <th><input type="checkbox" id="selectAllBankTransactions"></th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bankTransactionsTable">
                                    <!-- Dynamic content -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Book Records -->
                    <div class="col-lg-6">
                        <h6 class="font-weight-bold text-info mb-3">Book Records</h6>
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover table-sm">
                                <thead class="sticky-top bg-light">
                                    <tr>
                                        <th><input type="checkbox" id="selectAllBookTransactions"></th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bookTransactionsTable">
                                    <!-- Dynamic content -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reconciliation Actions -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-tools me-2"></i>Reconciliation Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Manual Adjustments</h6>
                        <form id="adjustmentForm">
                            <div class="mb-3">
                                <label class="form-label">Adjustment Type</label>
                                <select class="form-select" name="adjustment_type">
                                    <option value="bank_charge">Bank Charge</option>
                                    <option value="interest_earned">Interest Earned</option>
                                    <option value="error_correction">Error Correction</option>
                                    <option value="outstanding_check">Outstanding Check</option>
                                    <option value="deposit_in_transit">Deposit in Transit</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="addAdjustment()">Add Adjustment</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h6>Reconciliation Summary</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <td>Book Balance:</td>
                                    <td class="text-end" id="summaryBookBalance">TZS 0</td>
                                </tr>
                                <tr>
                                    <td>Add: Deposits in Transit:</td>
                                    <td class="text-end text-success" id="summaryDepositsInTransit">TZS 0</td>
                                </tr>
                                <tr>
                                    <td>Less: Outstanding Checks:</td>
                                    <td class="text-end text-danger" id="summaryOutstandingChecks">TZS 0</td>
                                </tr>
                                <tr>
                                    <td>Add/Less: Adjustments:</td>
                                    <td class="text-end" id="summaryAdjustments">TZS 0</td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>Adjusted Book Balance:</strong></td>
                                    <td class="text-end"><strong id="summaryAdjustedBalance">TZS 0</strong></td>
                                </tr>
                                <tr>
                                    <td>Bank Statement Balance:</td>
                                    <td class="text-end" id="summaryBankBalance">TZS 0</td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>Difference:</strong></td>
                                    <td class="text-end"><strong id="summaryDifference">TZS 0</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <button class="btn btn-success" onclick="completeReconciliation()" id="completeBtn" disabled>
                                <i class="bi bi-check-circle me-1"></i> Complete Reconciliation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Statement Modal -->
<div class="modal fade" id="importStatementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Bank Statement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="importForm">
                    <div class="mb-3">
                        <label class="form-label">Bank</label>
                        <select class="form-select" name="bank" required>
                            <option value="">Select Bank</option>
                            <option value="NMB">NMB Bank</option>
                            <option value="CRDB">CRDB Bank</option>
                            <option value="NBC">NBC Bank</option>
                            <option value="Mkombozi">Mkombozi Bank</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" class="form-control" name="account_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statement Period</label>
                        <div class="row">
                            <div class="col">
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statement File</label>
                        <input type="file" class="form-control" name="statement_file" accept=".csv,.xlsx,.pdf" required>
                        <div class="form-text">Supported formats: CSV, Excel, PDF</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="importStatement()">Import Statement</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Sample data for demonstration
const sampleBankTransactions = [
    { id: 1, date: '2024-01-15', description: 'Fee Payment - John Doe', amount: 500000, status: 'unmatched' },
    { id: 2, date: '2024-01-16', description: 'Salary Payment', amount: -1200000, status: 'unmatched' },
    { id: 3, date: '2024-01-17', description: 'Equipment Purchase', amount: -350000, status: 'unmatched' },
    { id: 4, date: '2024-01-18', description: 'Bank Charges', amount: -5000, status: 'unmatched' }
];

const sampleBookTransactions = [
    { id: 1, date: '2024-01-15', description: 'Student Fee Payment', amount: 500000, status: 'unmatched' },
    { id: 2, date: '2024-01-16', description: 'Teacher Salary', amount: -1200000, status: 'unmatched' },
    { id: 3, date: '2024-01-17', description: 'Office Equipment', amount: -350000, status: 'unmatched' }
];

function loadSchoolAccounts() {
    const schoolId = document.getElementById('schoolSelect').value;
    if (!schoolId) return;
    
    // Sample accounts - in real implementation, fetch from server
    const accounts = [
        { id: 1, bank: 'NMB Bank', account_number: '12345678901', balance: 15750000 },
        { id: 2, bank: 'CRDB Bank', account_number: '98765432109', balance: 8920000 }
    ];
    
    const accountSelect = document.getElementById('accountSelect');
    accountSelect.innerHTML = '<option value="">Select Account</option>';
    
    accounts.forEach(account => {
        const option = document.createElement('option');
        option.value = account.id;
        option.textContent = `${account.bank} - ${account.account_number}`;
        accountSelect.appendChild(option);
    });
}

function loadReconciliationData() {
    const accountId = document.getElementById('accountSelect').value;
    if (!accountId) return;
    
    // Show reconciliation panel
    document.getElementById('reconciliationPanel').style.display = 'block';
    
    // Load sample data
    loadAccountSummary();
    loadOutstandingItems();
    loadTransactionTables();
}

function loadAccountSummary() {
    document.getElementById('bankBalance').textContent = 'TZS 15,750,000';
    document.getElementById('bookBalance').textContent = 'TZS 15,725,000';
    document.getElementById('balanceDifference').textContent = 'TZS 25,000';
}

function loadOutstandingItems() {
    // Sample outstanding deposits
    const outstandingDeposits = [
        { date: '2024-01-19', description: 'Fee Payment - Jane Smith', amount: 450000, days: 3 },
        { date: '2024-01-20', description: 'Equipment Sale', amount: 75000, days: 2 }
    ];
    
    const depositsTable = document.getElementById('outstandingDepositsTable').getElementsByTagName('tbody')[0];
    depositsTable.innerHTML = '';
    
    outstandingDeposits.forEach((item, index) => {
        const row = depositsTable.insertRow();
        row.innerHTML = `
            <td><input type="checkbox" name="outstanding_deposits" value="${index}"></td>
            <td>${item.date}</td>
            <td>${item.description}</td>
            <td>TZS ${item.amount.toLocaleString()}</td>
            <td><span class="badge bg-warning">${item.days} days</span></td>
        `;
    });
    
    document.getElementById('outstandingDepositsCount').textContent = outstandingDeposits.length;
    document.getElementById('totalOutstandingDeposits').textContent = outstandingDeposits.reduce((sum, item) => sum + item.amount, 0).toLocaleString();
}

function loadTransactionTables() {
    // Load bank transactions
    const bankTable = document.getElementById('bankTransactionsTable');
    bankTable.innerHTML = '';
    
    sampleBankTransactions.forEach((transaction, index) => {
        const row = bankTable.insertRow();
        const statusClass = transaction.status === 'matched' ? 'bg-success' : 'bg-warning';
        row.innerHTML = `
            <td><input type="checkbox" name="bank_transactions" value="${transaction.id}"></td>
            <td>${transaction.date}</td>
            <td>${transaction.description}</td>
            <td class="${transaction.amount > 0 ? 'text-success' : 'text-danger'}">
                ${transaction.amount > 0 ? '+' : ''}TZS ${Math.abs(transaction.amount).toLocaleString()}
            </td>
            <td><span class="badge ${statusClass}">${transaction.status}</span></td>
        `;
    });
    
    // Load book transactions
    const bookTable = document.getElementById('bookTransactionsTable');
    bookTable.innerHTML = '';
    
    sampleBookTransactions.forEach((transaction, index) => {
        const row = bookTable.insertRow();
        const statusClass = transaction.status === 'matched' ? 'bg-success' : 'bg-warning';
        row.innerHTML = `
            <td><input type="checkbox" name="book_transactions" value="${transaction.id}"></td>
            <td>${transaction.date}</td>
            <td>${transaction.description}</td>
            <td class="${transaction.amount > 0 ? 'text-success' : 'text-danger'}">
                ${transaction.amount > 0 ? '+' : ''}TZS ${Math.abs(transaction.amount).toLocaleString()}
            </td>
            <td><span class="badge ${statusClass}">${transaction.status}</span></td>
        `;
    });
}

function toggleAllDeposits() {
    const checkboxes = document.querySelectorAll('input[name="outstanding_deposits"]');
    const selectAll = document.getElementById('selectAllDeposits').checked;
    checkboxes.forEach(checkbox => checkbox.checked = selectAll);
}

function toggleAllWithdrawals() {
    const checkboxes = document.querySelectorAll('input[name="outstanding_withdrawals"]');
    const selectAll = document.getElementById('selectAllWithdrawals').checked;
    checkboxes.forEach(checkbox => checkbox.checked = selectAll);
}

function matchSimilar() {
    alert('Automatically matching similar transactions based on amount and date...');
    // In real implementation, this would call the server to match transactions
}

function matchSelected() {
    const selectedBank = document.querySelectorAll('input[name="bank_transactions"]:checked');
    const selectedBook = document.querySelectorAll('input[name="book_transactions"]:checked');
    
    if (selectedBank.length === 0 || selectedBook.length === 0) {
        alert('Please select transactions from both bank statement and book records to match.');
        return;
    }
    
    alert(`Matching ${selectedBank.length} bank transactions with ${selectedBook.length} book transactions...`);
    // In real implementation, this would call the server to match selected transactions
}

function addAdjustment() {
    const form = document.getElementById('adjustmentForm');
    const formData = new FormData(form);
    
    if (!formData.get('amount') || !formData.get('description')) {
        alert('Please fill in all adjustment fields.');
        return;
    }
    
    alert('Adjustment added successfully.');
    form.reset();
    updateReconciliationSummary();
}

function updateReconciliationSummary() {
    // Update summary calculations
    document.getElementById('summaryBookBalance').textContent = 'TZS 15,725,000';
    document.getElementById('summaryDepositsInTransit').textContent = 'TZS 525,000';
    document.getElementById('summaryOutstandingChecks').textContent = 'TZS 0';
    document.getElementById('summaryAdjustments').textContent = 'TZS -5,000';
    document.getElementById('summaryAdjustedBalance').textContent = 'TZS 16,245,000';
    document.getElementById('summaryBankBalance').textContent = 'TZS 15,750,000';
    document.getElementById('summaryDifference').textContent = 'TZS 495,000';
    
    // Enable complete button if difference is acceptable
    const difference = Math.abs(495000);
    if (difference <= 1000) { // Allow small differences
        document.getElementById('completeBtn').disabled = false;
    }
}

function completeReconciliation() {
    if (confirm('Are you sure you want to complete this reconciliation?')) {
        alert('Reconciliation completed successfully!');
        // In real implementation, this would save the reconciliation
    }
}

function autoReconcile() {
    alert('Running automatic reconciliation...');
    // In real implementation, this would run auto-matching algorithms
}

function importStatement() {
    const form = document.getElementById('importForm');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    alert('Bank statement imported successfully!');
    bootstrap.Modal.getInstance(document.getElementById('importStatementModal')).hide();
    // In real implementation, this would upload and process the file
}

function exportReconciliationReport() {
    alert('Exporting reconciliation report...');
    // In real implementation, this would generate and download the report
}

// Initialize summary update
document.addEventListener('DOMContentLoaded', function() {
    updateReconciliationSummary();
});
</script>
@endpush

@push('styles')
<style>
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 1020;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}

.font-weight-bold {
    font-weight: 700 !important;
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .col-lg-6 {
        margin-bottom: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .btn {
        width: 100%;
    }
}
</style>
@endpush
