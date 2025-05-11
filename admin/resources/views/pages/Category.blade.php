<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Categories</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                        Add New Category
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Products Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="categoriesTableBody">
                                <!-- Categories will be loaded here via AJAX -->
                                <tr id="loading-row">
                                    <td colspan="6" class="text-center">
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

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="categoryId">
                    <div class="mb-3">
                        <label class="form-label" for="name">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="image">Category Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div id="currentImage" class="mt-2 d-none">
                            <img src="" alt="Current Image" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
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
                Are you sure you want to delete this category? This action cannot be undone.
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
    fetchCategories();
    setupEventListeners();
});

let currentCategoryId = null;

function setupEventListeners() {
    const categoryForm = document.getElementById('categoryForm');
    const confirmDeleteBtn = document.getElementById('confirmDelete');

    categoryForm.addEventListener('submit', handleCategorySubmit);
    confirmDeleteBtn.addEventListener('click', handleDelete);
}

function fetchCategories() {
    console.log('Fetching categories...');
    fetch('/api/categories')
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            displayCategories(data);
        })
        .catch(error => {
            console.error('Error fetching categories:', error);
            showError(`Error loading categories: ${error.message}`);
        });
}

function displayCategories(categories) {
    const tableBody = document.getElementById('categoriesTableBody');
    tableBody.innerHTML = '';
    
    if (categories.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">No categories found</td>
            </tr>
        `;
        return;
    }
    
    categories.forEach(category => {
        let imageHtml = '';
        if (category.image) {
            imageHtml = `<img src="/storage/${category.image}" alt="${category.name}" width="50">`;
        } else {
            imageHtml = `
                <div class="avatar">
                    <div class="avatar-initial bg-label-primary rounded">
                        ${category.name.charAt(0)}
                    </div>
                </div>
            `;
        }
        
        tableBody.innerHTML += `
            <tr>
                <td>${category.id}</td>
                <td>${imageHtml}</td>
                <td>${category.name}</td>
                <td>${category.description || 'N/A'}</td>
                <td>${category.products_count || 0}</td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="editCategory(${category.id})">
                                <i class="ri-pencil-line me-2"></i> Edit
                            </a>
                            <a class="dropdown-item" href="#" onclick="showDeleteModal(${category.id})">
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
    const tableBody = document.getElementById('categoriesTableBody');
    tableBody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center text-danger">${message}</td>
        </tr>
    `;
}

function handleCategorySubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const categoryId = document.getElementById('categoryId').value;
    
    const url = categoryId ? `/api/categories/${categoryId}` : '/api/categories';
    const method = categoryId ? 'PUT' : 'POST';

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
            const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
            modal.hide();
            fetchCategories();
            event.target.reset();
            document.getElementById('currentImage').classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Failed to save category');
    });
}

function editCategory(id) {
    currentCategoryId = id;
    fetch(`/api/categories/${id}`)
        .then(response => response.json())
        .then(category => {
            document.getElementById('categoryId').value = category.id;
            document.getElementById('name').value = category.name;
            document.getElementById('description').value = category.description || '';
            
            const currentImageDiv = document.getElementById('currentImage');
            if (category.image) {
                currentImageDiv.classList.remove('d-none');
                currentImageDiv.querySelector('img').src = `/storage/${category.image}`;
            } else {
                currentImageDiv.classList.add('d-none');
            }
            
            document.getElementById('modalTitle').textContent = 'Edit Category';
            const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Failed to load category details');
        });
}

function showDeleteModal(id) {
    currentCategoryId = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function handleDelete() {
    if (!currentCategoryId) return;

    fetch(`/api/categories/${currentCategoryId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (response.ok) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();
            fetchCategories();
        } else {
            throw new Error('Failed to delete category');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Failed to delete category');
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