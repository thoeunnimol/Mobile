<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Products</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
                        Add New Product
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="productsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                <!-- Products will be loaded here via AJAX -->
                                <tr id="loading-row">
                                    <td colspan="9" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="productId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="stock">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="category_id">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="brand">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="image">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Add this script at the end of the file -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchProducts();
    fetchCategories();
    setupEventListeners();
});

let currentProductId = null;

function setupEventListeners() {
    const productForm = document.getElementById('productForm');
    const confirmDeleteBtn = document.getElementById('confirmDelete');

    productForm.addEventListener('submit', handleProductSubmit);
    confirmDeleteBtn.addEventListener('click', handleDelete);
}

function fetchProducts() {
    console.log('Fetching products...');
    fetch('/api/products')
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            displayProducts(data);
        })
        .catch(error => {
            console.error('Error fetching products:', error);
            showError(`Error loading products: ${error.message}`);
        });
}

function fetchCategories() {
    fetch('/api/categories')
        .then(response => response.json())
        .then(categories => {
            const categorySelect = document.getElementById('category_id');
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching categories:', error));
}

function displayProducts(products) {
    const tableBody = document.getElementById('productsTableBody');
    tableBody.innerHTML = '';
    
    if (products.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center">No products found</td>
            </tr>
        `;
        return;
    }
    
    products.forEach(product => {
        const statusClass = product.status === 'published' ? 'success' : 
                           (product.status === 'draft' ? 'warning' : 'secondary');
        
        let imageHtml = '';
        if (product.image) {
            imageHtml = `<img src="/storage/${product.image}" alt="${product.name}" width="50">`;
        } else {
            imageHtml = `
                <div class="avatar">
                    <div class="avatar-initial bg-label-primary rounded">
                        ${product.name.charAt(0)}
                    </div>
                </div>
            `;
        }
        
        tableBody.innerHTML += `
            <tr>
                <td>${product.id}</td>
                <td>${imageHtml}</td>
                <td>${product.name}</td>
                <td>$${parseFloat(product.price).toFixed(2)}</td>
                <td>${product.stock}</td>
                <td>${product.category ? product.category.name : 'N/A'}</td>
                <td>${product.brand}</td>
                <td>
                    <span class="badge bg-label-${statusClass}">
                        ${product.status.charAt(0).toUpperCase() + product.status.slice(1)}
                    </span>
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="editProduct(${product.id})">
                                <i class="ri-pencil-line me-2"></i> Edit
                            </a>
                            <a class="dropdown-item" href="#" onclick="showDeleteModal(${product.id})">
                                <i class="ri-delete-bin-line me-2"></i> Delete
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });
}

function showError(message) {
    const tableBody = document.getElementById('productsTableBody');
    tableBody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center text-danger">${message}</td>
        </tr>
    `;
}

function handleProductSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const productId = document.getElementById('productId').value;
    
    const url = productId ? `/api/products/${productId}` : '/api/products';
    const method = productId ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            showValidationErrors(data.errors);
        } else {
            const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
            modal.hide();
            fetchProducts();
            event.target.reset();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Failed to save product');
    });
}

function editProduct(id) {
    currentProductId = id;
    fetch(`/api/products/${id}`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('productId').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('price').value = product.price;
            document.getElementById('stock').value = product.stock;
            document.getElementById('category_id').value = product.category_id;
            document.getElementById('brand').value = product.brand;
            document.getElementById('status').value = product.status;
            document.getElementById('description').value = product.description;
            
            document.getElementById('modalTitle').textContent = 'Edit Product';
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Failed to load product details');
        });
}

function showDeleteModal(id) {
    currentProductId = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function handleDelete() {
    if (!currentProductId) return;

    fetch(`/api/products/${currentProductId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (response.ok) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();
            fetchProducts();
        } else {
            throw new Error('Failed to delete product');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Failed to delete product');
    });
}

function showValidationErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

    // Show new errors
    Object.keys(errors).forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errors[field][0];
            input.parentNode.appendChild(feedback);
        }
    });
}
</script>