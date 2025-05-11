<!-- Add jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Customers</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal">
                        <i class="fas fa-plus"></i> Add New Customer
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table  table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customersTableBody">
                                <!-- Customer data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="customerForm">
                    @csrf
                    <input type="hidden" id="customerId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                            <div class="invalid-feedback" id="phoneError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                            <div class="invalid-feedback" id="addressError"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger" id="passwordRequired">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger" id="passwordConfirmationRequired">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <div class="invalid-feedback" id="passwordConfirmationError"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCustomer">Save Customer</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this customer?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>


<script>
let currentCustomerId = null;

// Fetch all customers
function fetchCustomers() {
    console.log('Fetching customers...');
    $.ajax({
        url: '/api/customers',
        method: 'GET',
        success: function(response) {
            console.log('API Response:', response);
            const tableBody = $('#customersTableBody');
            tableBody.empty();
            
            if (response.data && response.data.length > 0) {
                console.log('Found customers:', response.data.length);
                response.data.forEach(customer => {
                    const row = `
                        <tr>
                            <td>${customer.id}</td>
                            <td>${customer.name}</td>
                            <td>${customer.email}</td>
                            <td>${customer.phone || '-'}</td>
                            <td>${customer.address || '-'}</td>
                            <td>
                                <span class="badge bg-${customer.is_active ? 'success' : 'danger'}">
                                    ${customer.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        ${customer.is_active ? `
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="editCustomer(${customer.id})">
                                                <i class="ri-pencil-line me-2"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="showDeleteModal(${customer.id})">
                                                <i class="ri-delete-bin-line me-2"></i> Delete
                                            </a>
                                        ` : ''}
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="toggleCustomerStatus(${customer.id})">
                                            <i class="ri-${customer.is_active ? 'close-circle' : 'check-circle'}-line me-2"></i>
                                            ${customer.is_active ? 'Deactivate' : 'Activate'}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            } else {
                tableBody.append('<tr><td colspan="7" class="text-center">No customers found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error fetching customers:', xhr);
            console.error('Response:', xhr.responseText);
            alert('Error fetching customers. Please try again.');
        }
    });
}

// Handle form submission
$('#saveCustomer').click(function() {
    const formData = {
        name: $('#name').val(),
        email: $('#email').val(),
        phone: $('#phone').val(),
        address: $('#address').val(),
        password: $('#password').val(),
        password_confirmation: $('#password_confirmation').val(),
        is_active: $('#is_active').is(':checked') ? 1 : 0
    };

    const customerId = $('#customerId').val();
    const method = customerId ? 'PUT' : 'POST';
    const url = customerId ? `/api/customers/${customerId}` : '/api/customers';

    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#customerModal').modal('hide');
                fetchCustomers();
                resetForm();
                alert('Customer saved successfully!');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    $(`#${field}Error`).text(errors[field][0]);
                    $(`#${field}`).addClass('is-invalid');
                });
            } else {
                alert('Error saving customer. Please try again.');
            }
        }
    });
});

// Edit customer
function editCustomer(id) {
    $.ajax({
        url: `/api/customers/${id}`,
        method: 'GET',
        success: function(response) {
            const customer = response.data;
            $('#customerId').val(customer.id);
            $('#name').val(customer.name);
            $('#email').val(customer.email);
            $('#phone').val(customer.phone || '');
            $('#address').val(customer.address || '');
            $('#is_active').prop('checked', customer.is_active);
            $('#password').val('');
            $('#password_confirmation').val('');
            
            $('#passwordRequired').hide();
            $('#passwordConfirmationRequired').hide();
            
            $('#customerModalLabel').text('Edit Customer');
            $('#customerModal').modal('show');
        },
        error: function(xhr) {
            console.error('Error fetching customer:', xhr);
            alert('Error fetching customer details. Please try again.');
        }
    });
}

// Show delete confirmation modal
function showDeleteModal(id) {
    currentCustomerId = id;
    $('#deleteModal').modal('show');
}

// Handle delete confirmation
$('#confirmDelete').click(function() {
    if (currentCustomerId) {
        $.ajax({
            url: `/api/customers/${currentCustomerId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#deleteModal').modal('hide');
                    fetchCustomers();
                    alert('Customer deleted successfully!');
                }
            },
            error: function(xhr) {
                console.error('Error deleting customer:', xhr);
                alert('Error deleting customer. Please try again.');
            }
        });
    }
});

// Toggle customer status
function toggleCustomerStatus(id) {
    $.ajax({
        url: `/api/customers/${id}/toggle-active`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                fetchCustomers();
                alert('Customer status updated successfully!');
            }
        },
        error: function(xhr) {
            console.error('Error updating customer status:', xhr);
            alert('Error updating customer status. Please try again.');
        }
    });
}

// Reset form
function resetForm() {
    $('#customerForm')[0].reset();
    $('#customerId').val('');
    $('#customerModalLabel').text('Add New Customer');
    $('#passwordRequired').show();
    $('#passwordConfirmationRequired').show();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

// Initialize
$(document).ready(function() {
    fetchCustomers();
    
    // Reset form when modal is closed
    $('#customerModal').on('hidden.bs.modal', function() {
        resetForm();
    });
});
</script>

