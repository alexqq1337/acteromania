            </div><!-- /.admin-content -->
        </main>
    </div><!-- /.admin-wrapper -->
    
    <script>
    // Admin JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            });
        }
        
        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(function(alert) {
            setTimeout(function() {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 5000);
        });
        
        // Image preview function
        window.previewImage = function(input, previewId) {
            const preview = document.getElementById(previewId);
            if (!preview) return;
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                    preview.classList.add('has-image');
                };
                reader.readAsDataURL(input.files[0]);
            }
        };
        
        // Toast notification function
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            toast.innerHTML = `
                <div class="toast-content">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        ${type === 'success' ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>' : 
                          type === 'error' ? '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>' :
                          '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'}
                    </svg>
                    <span>${message}</span>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            `;
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        };
        
        // Confirm delete function
        window.confirmDelete = function(message = 'Sigur doriți să ștergeți acest element?') {
            return confirm(message);
        };
        
        // AJAX form submission helper
        window.submitForm = async function(form, callback) {
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message || 'Salvat cu succes!', 'success');
                    if (callback) callback(result);
                } else {
                    showToast(result.message || 'A apărut o eroare!', 'error');
                }
                
                return result;
            } catch (error) {
                showToast('Eroare de conexiune!', 'error');
                console.error('Form submission error:', error);
                return { success: false, message: error.message };
            }
        };
        
        // Sortable list initialization
        const sortableLists = document.querySelectorAll('.sortable-list');
        sortableLists.forEach(list => {
            let draggedItem = null;
            
            list.querySelectorAll('.sortable-item').forEach(item => {
                item.setAttribute('draggable', true);
                
                item.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    this.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                });
                
                item.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                    draggedItem = null;
                    
                    // Update order
                    const items = list.querySelectorAll('.sortable-item');
                    items.forEach((item, index) => {
                        const orderInput = item.querySelector('input[name*="order"]');
                        if (orderInput) orderInput.value = index + 1;
                    });
                });
                
                item.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    
                    if (this !== draggedItem) {
                        const rect = this.getBoundingClientRect();
                        const midY = rect.top + rect.height / 2;
                        
                        if (e.clientY < midY) {
                            list.insertBefore(draggedItem, this);
                        } else {
                            list.insertBefore(draggedItem, this.nextSibling);
                        }
                    }
                });
            });
        });
        
        // Character counter for textareas
        document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
            const maxLength = textarea.getAttribute('maxlength');
            const counter = document.createElement('div');
            counter.className = 'char-counter';
            counter.textContent = `0 / ${maxLength}`;
            textarea.parentNode.appendChild(counter);
            
            textarea.addEventListener('input', function() {
                counter.textContent = `${this.value.length} / ${maxLength}`;
                if (this.value.length > maxLength * 0.9) {
                    counter.style.color = '#dc3545';
                } else {
                    counter.style.color = '#6c757d';
                }
            });
            
            // Initialize counter
            counter.textContent = `${textarea.value.length} / ${maxLength}`;
        });
        
        // Toggle switch labels
        document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const label = this.closest('.toggle-switch').querySelector('.toggle-label-text');
                if (label) {
                    label.textContent = this.checked ? 
                        (label.dataset.on || 'Activ') : 
                        (label.dataset.off || 'Inactiv');
                }
            });
        });
    });
    </script>
</body>
</html>
