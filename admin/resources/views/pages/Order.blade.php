<!-- Add jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">Order Management</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orderModal">
                    <i class="ri-add-line me-2"></i> Create Order
                      </button>
                  </div>
                </div>
              </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#all-orders" role="tab">
                        <i class="ri-list-check me-2"></i> All Orders
                      </a>
                    </li>
                  </ul>
                </div>
              </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Order List</h5>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 300px;">
                    <span class="input-group-text bg-transparent">
                        <i class="ri-search-line"></i>
                          </span>
                    <input type="text" class="form-control border-start-0" id="searchOrder" 
                           placeholder="Search by Order ID or customer name...">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                        <i class="ri-close-line"></i>
                            </button>
                        </div>
                      </div>
                    </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                          <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                        <th class="text-center">Actions</th>
                          </tr>
                        </thead>
                <tbody id="ordersTableBody">
                    <!-- Orders will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="orderModalLabel">Create New Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    @csrf
                    <input type="hidden" id="orderId">
                    <div class="row g-3">
                        <!-- Order Date -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Order Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent">
                                    <i class="ri-calendar-line"></i>
                                </span>
                                <input type="date" class="form-control" id="orderDate" name="order_date" required>
                            </div>
                        </div>

                        <!-- Customer Selection -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Select Customer</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent">
                                    <i class="ri-user-line"></i>
                                </span>
                                <select class="form-select" id="customerId" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                </select>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Order Items</label>
                            <div id="orderItems">
                                <!-- Order items will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary mt-2" id="addItem">
                                <i class="ri-add-line me-2"></i>Add Item
                            </button>
                        </div>

                        <!-- Total Amount -->
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Total Amount</h6>
                                        <h4 class="mb-0 text-primary" id="totalAmount">$0.00</h4>
                                    </div>
                              </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Shipping Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent">
                                    <i class="ri-map-pin-line"></i>
                                </span>
                                <textarea class="form-control" id="shippingAddress" name="shipping_address" 
                                          rows="3" required placeholder="Enter shipping address"></textarea>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Billing Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent">
                                    <i class="ri-bank-card-line"></i>
                                </span>
                                <textarea class="form-control" id="billingAddress" name="billing_address" 
                                          rows="3" required placeholder="Enter billing address"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveOrder">Save Order</button>
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
                Are you sure you want to delete this order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;
let products = [];
let customers = [];

// Fetch all orders
function fetchOrders() {
    $.ajax({
        url: '/api/orders',
        method: 'GET',
        success: function(response) {
            const tableBody = $('#ordersTableBody');
            tableBody.empty();
            
            if (response.data && response.data.length > 0) {
                response.data.forEach(order => {
                    const row = `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg me-3">
                                        <div class="avatar-initial bg-label-primary rounded-circle">
                                            <i class="ri-shopping-cart-line"></i>
                                  </div>
                                </div>
                                <div>
                                        <h6 class="mb-1 fw-semibold">${order.order_number}</h6>
                                        <small class="text-muted">#${order.id}</small>
                                    </div>
                              </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-initial bg-label-info rounded-circle">
                                            <span>${order.customer?.name?.charAt(0)?.toUpperCase() || '?'}</span>
                                  </div>
                                </div>
                                <div>
                                        <h6 class="mb-0">${order.customer?.name || 'N/A'}</h6>
                                        <small class="text-muted">${order.customer?.email || 'N/A'}</small>
                                    </div>
                              </div>
                            </td>
                            <td>
                                <span class="text-muted">
                                    ${new Date(order.order_date).toLocaleDateString()}
                              </span>
                            </td>
                            <td>
                                <span class="fw-semibold">$${order.total_amount}</span>
                              <br />
                                <small class="text-muted">
                                    ${order.items?.length || 0} items
                              </small>
                            </td>
                            <td>
                                <span class="badge bg-${order.payment_status === 'paid' ? 'success' : 'warning'}">
                                    ${order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1)}
                                </span>
                              </td>
                              <td>
                                <span class="badge bg-${getStatusColor(order.status)}">
                                    ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                </span>
                              </td>
                              <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                  </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="editOrder(${order.id})">
                                            <i class="ri-pencil-line me-2"></i> Edit
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="showDeleteModal(${order.id})">
                                            <i class="ri-delete-bin-line me-2"></i> Delete
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="updateOrderStatus(${order.id}, 'completed')">
                                            <i class="ri-check-line me-2"></i> Complete Order
                                        </a>
                                    </div>
                                </div>
                              </td>
                            </tr>
                    `;
                    tableBody.append(row);
                });
            } else {
                tableBody.append('<tr><td colspan="7" class="text-center">No orders found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error fetching orders:', xhr);
            alert('Error fetching orders. Please try again.');
        }
    });
}

// Get status color
function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'processing': 'info',
        'completed': 'success',
        'cancelled': 'danger'
    };
    return colors[status] || 'secondary';
}

// Fetch products and customers for dropdowns
function fetchProductsAndCustomers() {
    // Fetch products
    $.ajax({
        url: '/api/products',
        method: 'GET',
        success: function(response) {
            products = response.data;
        }
    });

    // Fetch customers
    $.ajax({
        url: '/api/customers',
        method: 'GET',
        success: function(response) {
            customers = response.data;
            const customerSelect = $('#customerId');
            customerSelect.empty().append('<option value="">Select Customer</option>');
            customers.forEach(customer => {
                customerSelect.append(`<option value="${customer.id}">${customer.name} - ${customer.email}</option>`);
            });
        }
    });
}

// Add order item row
function addOrderItem() {
    const itemHtml = `
        <div class="row g-3 mb-2 border rounded p-3 order-item">
            <div class="col-md-5">
                <label class="form-label">Product</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent">
                        <i class="ri-shopping-bag-line"></i>
                                </span>
                    <select class="form-select product-select" required>
                        <option value="">Select Product</option>
                        ${products.map(product => `
                            <option value="${product.id}" data-price="${product.price}">
                                ${product.name} - $${product.price}
                          </option>
                        `).join('')}
                      </select>
                </div>
                          </div>
            <div class="col-md-3">
                <label class="form-label">Quantity</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent">
                        <i class="ri-number-1"></i>
                            </span>
                    <input type="number" class="form-control quantity-input" value="1" min="1" required>
                      </div>
                    </div>
            <div class="col-md-3">
                <label class="form-label">Unit Price</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent">
                        <i class="ri-money-dollar-circle-line"></i>
                                </span>
                    <input type="number" class="form-control unit-price" readonly>
                </div>
                        </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger remove-item">
                    <i class="ri-close-line"></i>
                                    </button>
          </div>
        </div>
    `;
    $('#orderItems').append(itemHtml);
    updateTotalAmount();
}

// Update total amount
function updateTotalAmount() {
    let total = 0;
    $('.order-item').each(function() {
        const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
        const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
        total += quantity * unitPrice;
    });
    $('#totalAmount').text(`$${total.toFixed(2)}`);
}

// Handle form submission
$('#saveOrder').click(function() {
    const formData = {
        customer_id: $('#customerId').val(),
        order_date: $('#orderDate').val(),
        shipping_address: $('#shippingAddress').val(),
        billing_address: $('#billingAddress').val(),
        items: []
    };

    $('.order-item').each(function() {
        const productId = $(this).find('.product-select').val();
        const quantity = $(this).find('.quantity-input').val();
        const unitPrice = $(this).find('.unit-price').val();
        
        if (productId && quantity) {
            formData.items.push({
                product_id: productId,
                quantity: quantity,
                unit_price: unitPrice
            });
        }
    });

    const orderId = $('#orderId').val();
    const method = orderId ? 'PUT' : 'POST';
    const url = orderId ? `/api/orders/${orderId}` : '/api/orders';

    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#orderModal').modal('hide');
                fetchOrders();
                resetForm();
                alert('Order saved successfully!');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    alert(errors[field][0]);
                });
            } else {
                alert('Error saving order. Please try again.');
            }
        }
    });
});

// Edit order
function editOrder(id) {
    $.ajax({
        url: `/api/orders/${id}`,
        method: 'GET',
        success: function(response) {
            const order = response.data;
            $('#orderId').val(order.id);
            $('#customerId').val(order.customer_id);
            $('#orderDate').val(order.order_date);
            $('#shippingAddress').val(order.shipping_address);
            $('#billingAddress').val(order.billing_address);
            
            // Clear existing items
            $('#orderItems').empty();
            
            // Add order items
            order.items.forEach(item => {
                addOrderItem();
                const lastItem = $('.order-item').last();
                lastItem.find('.product-select').val(item.product_id);
                lastItem.find('.quantity-input').val(item.quantity);
                lastItem.find('.unit-price').val(item.unit_price);
            });
            
            $('#orderModalLabel').text('Edit Order');
            $('#orderModal').modal('show');
        },
        error: function(xhr) {
            console.error('Error fetching order:', xhr);
            alert('Error fetching order details. Please try again.');
        }
    });
}

// Show delete confirmation modal
function showDeleteModal(id) {
    currentOrderId = id;
    $('#deleteModal').modal('show');
}

// Handle delete confirmation
$('#confirmDelete').click(function() {
    if (currentOrderId) {
        $.ajax({
            url: `/api/orders/${currentOrderId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#deleteModal').modal('hide');
                    fetchOrders();
                    alert('Order deleted successfully!');
                }
            },
            error: function(xhr) {
                console.error('Error deleting order:', xhr);
                alert('Error deleting order. Please try again.');
            }
        });
    }
});

// Update order status
function updateOrderStatus(id, status) {
    $.ajax({
        url: `/api/orders/${id}/status`,
        method: 'PUT',
        data: { status: status },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                fetchOrders();
                alert('Order status updated successfully!');
            }
        },
        error: function(xhr) {
            console.error('Error updating order status:', xhr);
            alert('Error updating order status. Please try again.');
        }
    });
}

// Reset form
function resetForm() {
    $('#orderForm')[0].reset();
    $('#orderId').val('');
    $('#orderModalLabel').text('Create New Order');
    $('#orderItems').empty();
    addOrderItem();
}

// Event Listeners
$(document).ready(function() {
    fetchOrders();
    fetchProductsAndCustomers();
    
    // Add item button
    $('#addItem').click(addOrderItem);
    
    // Remove item button
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.order-item').remove();
        updateTotalAmount();
    });
    
    // Product selection change
    $(document).on('change', '.product-select', function() {
        const price = $(this).find(':selected').data('price');
        $(this).closest('.order-item').find('.unit-price').val(price);
        updateTotalAmount();
    });
    
    // Quantity change
    $(document).on('change', '.quantity-input', updateTotalAmount);
    
    // Search functionality
    $('#searchOrder').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('#ordersTableBody tr').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(searchTerm));
        });
    });
    
    // Clear search
    $('#clearSearch').click(function() {
        $('#searchOrder').val('');
        $('#ordersTableBody tr').show();
    });
    
    // Reset form when modal is closed
    $('#orderModal').on('hidden.bs.modal', function() {
        resetForm();
    });
});
</script>