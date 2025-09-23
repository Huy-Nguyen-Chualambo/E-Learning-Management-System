// Admin JavaScript Module
class AdminApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupAjaxDefaults();
        this.setupSearchDebounce();
        this.setupModals();
        this.setupNotifications();
    }

    setupAjaxDefaults() {
        // Setup CSRF token for all AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
    }

    setupSearchDebounce() {
        // Debounce search inputs
        const searchInputs = document.querySelectorAll('.search-input');
        searchInputs.forEach(input => {
            let timeout;
            input.addEventListener('input', (e) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.performSearch(e.target.value, e.target.dataset.searchUrl);
                }, 500);
            });
        });
    }

    setupModals() {
        // Setup Bootstrap modals
        this.modals = {};
        const modalElements = document.querySelectorAll('.modal');
        modalElements.forEach(modal => {
            const modalId = modal.id;
            this.modals[modalId] = new bootstrap.Modal(modal);
        });
    }

    setupNotifications() {
        // Setup toast notifications
        this.toastContainer = document.getElementById('toast-container');
        if (!this.toastContainer) {
            this.toastContainer = document.createElement('div');
            this.toastContainer.id = 'toast-container';
            this.toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(this.toastContainer);
        }
    }

    performSearch(keyword, url) {
        if (!keyword.trim()) return;

        fetch(`${url}?keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.updateSearchResults(data.data);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                this.showNotification('Search failed', 'error');
            });
    }

    updateSearchResults(results) {
        const resultsContainer = document.getElementById('search-results');
        if (resultsContainer) {
            resultsContainer.innerHTML = this.renderSearchResults(results);
        }
    }

    renderSearchResults(results) {
        if (!results || results.length === 0) {
            return '<div class="alert alert-info">No results found</div>';
        }

        return results.map(result => `
            <div class="search-result-item">
                <h6>${result.name}</h6>
                <p class="text-muted">${result.email || result.description || ''}</p>
            </div>
        `).join('');
    }

    showModal(modalId) {
        if (this.modals[modalId]) {
            this.modals[modalId].show();
        }
    }

    hideModal(modalId) {
        if (this.modals[modalId]) {
            this.modals[modalId].hide();
        }
    }

    showNotification(message, type = 'info') {
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;

        this.toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    // Generic CRUD methods
    async createRecord(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                this.showNotification(result.message, 'success');
                return result.data;
            } else {
                this.showNotification(result.message, 'error');
                return null;
            }
        } catch (error) {
            console.error('Create error:', error);
            this.showNotification('Failed to create record', 'error');
            return null;
        }
    }

    async updateRecord(url, data) {
        try {
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                this.showNotification(result.message, 'success');
                return result.data;
            } else {
                this.showNotification(result.message, 'error');
                return null;
            }
        } catch (error) {
            console.error('Update error:', error);
            this.showNotification('Failed to update record', 'error');
            return null;
        }
    }

    async deleteRecord(url) {
        if (!confirm('Are you sure you want to delete this record?')) {
            return false;
        }

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();
            
            if (result.success) {
                this.showNotification(result.message, 'success');
                return true;
            } else {
                this.showNotification(result.message, 'error');
                return false;
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showNotification('Failed to delete record', 'error');
            return false;
        }
    }
}

// Initialize admin app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.adminApp = new AdminApp();
}); 