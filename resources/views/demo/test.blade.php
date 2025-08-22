<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demo Request Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Test Demo Request</h4>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#demoRequestModal">
                            <i class="fas fa-play me-2"></i>Request Demo
                        </button>
                        
                        @if(session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Request Modal -->
    <div class="modal fade" id="demoRequestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Demo Access</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="demoRequestForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Organization Name *</label>
                                <input type="text" class="form-control" name="organization_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Organization Contact *</label>
                                <input type="text" class="form-control" name="organization_contact" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Name *</label>
                                <input type="text" class="form-control" name="contact_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Phone *</label>
                                <input type="tel" class="form-control" name="contact_phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Email *</label>
                                <input type="email" class="form-control" name="contact_email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country *</label>
                                <select class="form-control" name="organization_country" required>
                                    <option value="">Select Country</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Address *</label>
                                <textarea class="form-control" name="organization_address" required></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total Schools *</label>
                                <input type="number" class="form-control" name="total_schools" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitDemoRequest">Submit Request</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#submitDemoRequest').on('click', function() {
            const form = $('#demoRequestForm');
            const btn = $(this);
            
            if (!form[0].checkValidity()) {
                form[0].reportValidity();
                return;
            }

            btn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: '{{ route("demo.request") }}',
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
                },
                success: function(response) {
                    alert('Success: ' + response.message);
                    $('#demoRequestModal').modal('hide');
                    form[0].reset();
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                },
                complete: function() {
                    btn.prop('disabled', false).text('Submit Request');
                }
            });
        });
    </script>
</body>
</html>
