// Admin Panel JavaScript

// Handle file upload
document.getElementById('productImageUpload')?.addEventListener('change', async function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('file', file);
    
    const progress = document.getElementById('uploadProgress');
    progress.style.display = 'block';
    progress.textContent = 'Uploading...';
    
    try {
        const response = await fetch('upload_file.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('productImage').value = result.url;
            progress.textContent = 'Upload successful!';
            progress.style.color = 'green';
            setTimeout(() => {
                progress.style.display = 'none';
                progress.style.color = '';
            }, 2000);
        } else {
            progress.textContent = 'Upload failed: ' + result.error;
            progress.style.color = 'red';
        }
    } catch (error) {
        progress.textContent = 'Upload error: ' + error.message;
        progress.style.color = 'red';
    }
});

function openAddModal() {
    const modal = document.getElementById('productModal');
    document.getElementById('modalTitle').textContent = 'Add New Product';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('productImage').value = '';
    document.getElementById('productActive').checked = true;
    document.getElementById('currentImage').style.display = 'none';
    document.getElementById('productImageUpload').required = true;
    
    modal.classList.add('active');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function editProduct(product) {
    const modal = document.getElementById('productModal');
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = product.id;
    document.getElementById('productNameInput').value = product.name;
    document.getElementById('productDescription').value = product.description;
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productImage').value = product.image;
    document.getElementById('productActive').checked = product.active !== false;
    document.getElementById('productImageUpload').required = false;
    
    // Show current image
    const currentImageDiv = document.getElementById('currentImage');
    const currentImageName = document.getElementById('currentImageName');
    currentImageName.textContent = product.image;
    currentImageDiv.style.display = 'block';
    
    modal.classList.add('active');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.classList.remove('active');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('productForm').reset();
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch('delete_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'admin.php?deleted=1';
            } else {
                alert('Error deleting product. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting product. Please try again.');
        });
    }
}

// Close modal when clicking outside
document.getElementById('productModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeProductModal();
    }
});

// Handle product form submission
document.getElementById('productForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('save_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const action = document.getElementById('productId').value ? 'updated' : 'added';
            window.location.href = 'admin.php?' + action + '=1';
        } else {
            alert('Error saving product. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving product. Please try again.');
    });
});
