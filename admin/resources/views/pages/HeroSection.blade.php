
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Hero Sections</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#heroSectionModal">
                        Add New Hero Section
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Page Name</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Image</th>
                                    <th>Button Text</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="heroSectionsTableBody">
                                <!-- Hero section data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hero Section Modal -->
<div class="modal fade" id="heroSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="heroSectionModalLabel">Add New Hero Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="heroSectionForm">
                    <input type="hidden" id="heroSectionId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="page_name" class="form-label">Page Name</label>
                            <input type="text" class="form-control" id="page_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <textarea class="form-control" id="subtitle" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="button_text" class="form-label">Button Text</label>
                            <input type="text" class="form-control" id="button_text">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="button_link" class="form-label">Button Link</label>
                            <input type="text" class="form-control" id="button_link">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" accept="image/*">
                            <div id="currentImage" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveHeroSection">Save</button>
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
                Are you sure you want to delete this hero section?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentHeroSectionId = null;

// Fetch all hero sections
function fetchHeroSections() {
    fetch('/api/hero-sections')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('heroSectionsTableBody');
            tableBody.innerHTML = '';
            
            data.forEach(heroSection => {
                const row = `
                    <tr>
                        <td>${heroSection.page_name}</td>
                        <td>${heroSection.title}</td>
                        <td>${heroSection.subtitle}</td>
                        <td>
                            ${heroSection.image ? 
                                `<img src="/storage/${heroSection.image}" alt="${heroSection.title}" style="max-width: 100px;">` : 
                                'No Image'}
                        </td>
                        <td>${heroSection.button_text || '-'}</td>
                        <td>
                            <span class="badge bg-${heroSection.is_active ? 'success' : 'danger'}">
                                ${heroSection.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editHeroSection(${heroSection.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="showDeleteModal(${heroSection.id})">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error:', error));
}

// Handle form submission
document.getElementById('saveHeroSection').addEventListener('click', function() {
    const formData = new FormData();
    formData.append('page_name', document.getElementById('page_name').value);
    formData.append('title', document.getElementById('title').value);
    formData.append('subtitle', document.getElementById('subtitle').value);
    formData.append('button_text', document.getElementById('button_text').value);
    formData.append('button_link', document.getElementById('button_link').value);
    formData.append('is_active', document.getElementById('is_active').checked ? 1 : 0);
    
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    const heroSectionId = document.getElementById('heroSectionId').value;
    const method = heroSectionId ? 'PUT' : 'POST';
    const url = heroSectionId ? `/api/hero-sections/${heroSectionId}` : '/api/hero-sections';

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.id) {
            $('#heroSectionModal').modal('hide');
            fetchHeroSections();
            resetForm();
        } else {
            alert('Error: ' + (data.message || 'Failed to save hero section'));
        }
    })
    .catch(error => console.error('Error:', error));
});

// Edit hero section
function editHeroSection(id) {
    fetch(`/api/hero-sections/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('heroSectionId').value = data.id;
            document.getElementById('page_name').value = data.page_name;
            document.getElementById('title').value = data.title;
            document.getElementById('subtitle').value = data.subtitle;
            document.getElementById('button_text').value = data.button_text || '';
            document.getElementById('button_link').value = data.button_link || '';
            document.getElementById('is_active').checked = data.is_active;
            
            if (data.image) {
                document.getElementById('currentImage').innerHTML = `
                    <img src="/storage/${data.image}" alt="Current Image" style="max-width: 200px;">
                `;
            } else {
                document.getElementById('currentImage').innerHTML = '';
            }
            
            document.getElementById('heroSectionModalLabel').textContent = 'Edit Hero Section';
            $('#heroSectionModal').modal('show');
        })
        .catch(error => console.error('Error:', error));
}

// Show delete confirmation modal
function showDeleteModal(id) {
    currentHeroSectionId = id;
    $('#deleteModal').modal('show');
}

// Handle delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentHeroSectionId) {
        fetch(`/api/hero-sections/${currentHeroSectionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                $('#deleteModal').modal('hide');
                fetchHeroSections();
            } else {
                alert('Error: ' + (data.message || 'Failed to delete hero section'));
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

// Reset form
function resetForm() {
    document.getElementById('heroSectionForm').reset();
    document.getElementById('heroSectionId').value = '';
    document.getElementById('currentImage').innerHTML = '';
    document.getElementById('heroSectionModalLabel').textContent = 'Add New Hero Section';
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    fetchHeroSections();
});
</script>
@endpush 