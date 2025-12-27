/**
 * Ana JavaScript Dosyası
 * AJAX işlemleri ve dinamik fonksiyonlar
 */

// AJAX Helper
async function fetchAPI(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    if (data) {
        if (method === 'GET') {
            url += '?' + new URLSearchParams(data);
        } else {
            data[CSRF_TOKEN_NAME] = CSRF_TOKEN;
            options.body = JSON.stringify(data);
        }
    }
    
    try {
        const response = await fetch(BASE_URL + '/' + url, options);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('API Error:', error);
        return { success: false, message: 'Bir hata oluştu' };
    }
}

// FormData ile dosya yükleme
async function uploadFormData(url, formData) {
    formData.append(CSRF_TOKEN_NAME, CSRF_TOKEN);
    
    try {
        const response = await fetch(BASE_URL + '/' + url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        return await response.json();
    } catch (error) {
        console.error('Upload Error:', error);
        return { success: false, message: 'Dosya yüklenirken hata oluştu' };
    }
}

// Alert gösterme
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
        <span>${message}</span>
        <button class="alert-close">&times;</button>
    `;
    
    document.body.insertBefore(alertDiv, document.body.firstChild);
    
    // Kapatma butonu
    alertDiv.querySelector('.alert-close').addEventListener('click', () => {
        alertDiv.remove();
    });
    
    // Otomatik kaybolma
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
}

// Confirm dialog
function confirmAction(message) {
    return confirm(message);
}

// Form validasyonu
function validateForm(formElement) {
    const inputs = formElement.querySelectorAll('[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        const errorSpan = input.nextElementSibling;
        
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            
            if (errorSpan && errorSpan.classList.contains('form-error')) {
                errorSpan.textContent = 'Bu alan zorunludur';
            }
        } else {
            input.classList.remove('error');
            
            if (errorSpan && errorSpan.classList.contains('form-error')) {
                errorSpan.textContent = '';
            }
        }
    });
    
    return isValid;
}

// Bildirimleri yükle
async function loadNotifications() {
    const result = await fetchAPI('api/notifications');
    
    if (result.success) {
        updateNotificationBadge(result.unread_count);
        // Bildirim listesini güncelle
    }
}

// Bildirim badge'ini güncelle
function updateNotificationBadge(count) {
    const badge = document.querySelector('.navbar-link .badge');
    
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Başvuru durumu güncelleme
async function updateApplicationStatus(applicationId, status) {
    if (!confirmAction('Başvuru durumunu güncellemek istediğinizden emin misiniz?')) {
        return;
    }
    
    const result = await fetchAPI('employer/update-application-status', 'POST', {
        application_id: applicationId,
        status: status
    });
    
    if (result.success) {
        showAlert('Durum güncellendi', 'success');
        setTimeout(() => location.reload(), 1000);
    } else {
        showAlert(result.message, 'error');
    }
}

// Bildirim okundu işaretle
async function markNotificationRead(notificationId) {
    await fetchAPI('applicant/mark-notification-read/' + notificationId, 'POST');
    loadNotifications();
}

// CV önizleme
function previewCV(input) {
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        const preview = document.getElementById('cv-preview');
        
        if (preview) {
            preview.innerHTML = `
                <div class="file-info">
                    <i class="fas fa-file-pdf"></i>
                    <span>${file.name}</span>
                    <span>(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
            `;
        }
    }
}

// Form alanı ekleme (dinamik)
function addFormField() {
    const container = document.getElementById('form-fields-container');
    const template = document.getElementById('form-field-template');
    
    if (container && template) {
        const newField = template.content.cloneNode(true);
        container.appendChild(newField);
    }
}

// Form alanı silme
function removeFormField(button) {
    if (confirmAction('Bu alanı silmek istediğinizden emin misiniz?')) {
        button.closest('.form-field-item').remove();
    }
}

// Skor badge rengi
function getScoreBadgeClass(score) {
    if (score >= 80) return 'high';
    if (score >= 60) return 'medium';
    return 'low';
}

// Tarih formatlama
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 60) {
        return minutes + ' dakika önce';
    } else if (hours < 24) {
        return hours + ' saat önce';
    } else if (days < 30) {
        return days + ' gün önce';
    } else {
        return date.toLocaleDateString('tr-TR');
    }
}

// Arama debounce
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    // Bildirim kontrolü (giriş yapmışsa)
    if (typeof CSRF_TOKEN !== 'undefined') {
        // İlk yükleme
        // loadNotifications();
        
        // Her 60 saniyede bir kontrol et
        // setInterval(loadNotifications, 60000);
    }
    
    // Otomatik form submit (arama)
    const searchInputs = document.querySelectorAll('[data-auto-submit]');
    searchInputs.forEach(input => {
        input.addEventListener('input', debounce(function() {
            this.form.submit();
        }, 500));
    });
    
    // Tooltip init (varsa)
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
            tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) tooltip.remove();
        });
    });
});

// Global fonksiyonlar
window.fetchAPI = fetchAPI;
window.uploadFormData = uploadFormData;
window.showAlert = showAlert;
window.confirmAction = confirmAction;
window.validateForm = validateForm;
window.updateApplicationStatus = updateApplicationStatus;
window.markNotificationRead = markNotificationRead;
window.previewCV = previewCV;
window.addFormField = addFormField;
window.removeFormField = removeFormField;
