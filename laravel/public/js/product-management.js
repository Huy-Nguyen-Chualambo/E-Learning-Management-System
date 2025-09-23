// Product Management Module
class ProductManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadProducts();
    }

    setupEventListeners() {
        // Create product form
        const createForm = document.getElementById('create-product-form');
        if (createForm) {
            createForm.addEventListener('submit', (e) => this.handleCreateProduct(e));
        }

        // Edit product form
        const editForm = document.getElementById('edit-product-form');
        if (editForm) {
            editForm.addEventListener('submit', (e) => this.handleUpdateProduct(e));
        }

        // Search products
        const searchInput = document.getElementById('product-search');
        if (searchInput) {
            let timeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.searchProducts(e.target.value);
                }, 500);
            });
        }

        // Filter by category
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => {
                this.filterByCategory(e.target.value);
            });
        }

        // Price range filter
        const priceFilter = document.getElementById('price-filter');
        if (priceFilter) {
            priceFilter.addEventListener('change', (e) => {
                this.filterByPrice(e.target.value);
            });
        }
    }

    async loadProducts() {
        try {
            const response = await fetch('/api/products');
            const result = await response.json();
            
            if (result.success) {
                this.renderProducts(result.data);
            }
        } catch (error) {
            console.error('Failed to load products:', error);
            window.adminApp.showNotification('Failed to load products', 'error');
        }
    }

    renderProducts(products) {
        const container = document.getElementById('products-container');
        if (!container) return;

        if (!products || products.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No products found</div>';
            return;
        }

        container.innerHTML = products.map(product => this.renderProductCard(product)).join('');
    }

    renderProductCard(product) {
        const categories = product.categories ? product.categories.map(cat => cat.name).join(', ') : 'No categories';
        const finalPrice = product.sale_price || product.price;
        const originalPrice = product.sale_price ? `<small class="text-muted text-decoration-line-through">$${product.price}</small>` : '';

        return `
            <div class="col-md-4 mb-4">
                <div class="card product-card" data-product-id="${product.id}">
                    <img src="${product.image_path}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text">${product.description || 'No description'}</p>
                        <p class="text-muted small">Categories: ${categories}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h5 text-primary">$${finalPrice}</span>
                                ${originalPrice}
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" onclick="productManager.editProduct(${product.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="productManager.deleteProduct(${product.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    async handleCreateProduct(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        // Handle categories array
        const categories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked')).map(cb => parseInt(cb.value));
        data.categories = categories;

        const result = await window.adminApp.createRecord('/api/products', data);
        
        if (result) {
            e.target.reset();
            window.adminApp.hideModal('createProductModal');
            this.loadProducts();
        }
    }

    async handleUpdateProduct(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        const productId = e.target.dataset.productId;
        
        // Handle categories array
        const categories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked')).map(cb => parseInt(cb.value));
        data.categories = categories;

        const result = await window.adminApp.updateRecord(`/api/products/${productId}`, data);
        
        if (result) {
            window.adminApp.hideModal('editProductModal');
            this.loadProducts();
        }
    }

    async editProduct(productId) {
        try {
            const response = await fetch(`/api/products/${productId}`);
            const result = await response.json();
            
            if (result.success) {
                this.populateEditForm(result.data);
                window.adminApp.showModal('editProductModal');
            }
        } catch (error) {
            console.error('Failed to load product:', error);
            window.adminApp.showNotification('Failed to load product', 'error');
        }
    }

    populateEditForm(product) {
        const form = document.getElementById('edit-product-form');
        if (!form) return;

        form.dataset.productId = product.id;
        form.querySelector('input[name="name"]').value = product.name || '';
        form.querySelector('textarea[name="description"]').value = product.description || '';
        form.querySelector('textarea[name="content"]').value = product.content || '';
        form.querySelector('input[name="price"]').value = product.price || '';
        form.querySelector('input[name="sale_price"]').value = product.sale_price || '';
        form.querySelector('input[name="duration"]').value = product.duration || '';
        form.querySelector('select[name="level"]').value = product.level || 'beginner';
        form.querySelector('input[name="is_active"]').checked = product.is_active;
        form.querySelector('input[name="is_featured"]').checked = product.is_featured;
        form.querySelector('input[name="sort_order"]').value = product.sort_order || '';

        // Handle categories
        const categoryCheckboxes = form.querySelectorAll('input[name="categories[]"]');
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = product.categories && product.categories.some(cat => cat.id == checkbox.value);
        });
    }

    async deleteProduct(productId) {
        const success = await window.adminApp.deleteRecord(`/api/products/${productId}`);
        
        if (success) {
            this.loadProducts();
        }
    }

    async searchProducts(keyword) {
        if (!keyword.trim()) {
            this.loadProducts();
            return;
        }

        try {
            const response = await fetch(`/api/products/search?keyword=${encodeURIComponent(keyword)}`);
            const result = await response.json();
            
            if (result.success) {
                this.renderProducts(result.data);
            }
        } catch (error) {
            console.error('Search failed:', error);
            window.adminApp.showNotification('Search failed', 'error');
        }
    }

    async filterByCategory(categoryId) {
        if (!categoryId) {
            this.loadProducts();
            return;
        }

        try {
            const response = await fetch(`/api/products/category/${categoryId}`);
            const result = await response.json();
            
            if (result.success) {
                this.renderProducts(result.data);
            }
        } catch (error) {
            console.error('Filter failed:', error);
            window.adminApp.showNotification('Filter failed', 'error');
        }
    }

    async filterByPrice(priceRange) {
        if (!priceRange) {
            this.loadProducts();
            return;
        }

        const [minPrice, maxPrice] = priceRange.split('-').map(p => parseFloat(p));
        
        try {
            const response = await fetch(`/api/products/search?min_price=${minPrice}&max_price=${maxPrice}`);
            const result = await response.json();
            
            if (result.success) {
                this.renderProducts(result.data);
            }
        } catch (error) {
            console.error('Price filter failed:', error);
            window.adminApp.showNotification('Price filter failed', 'error');
        }
    }
}

// Initialize product manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.productManager = new ProductManager();
}); 