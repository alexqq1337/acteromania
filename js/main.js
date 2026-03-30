/**
 * ActeRomânia - Professional Legal Website
 * JavaScript functionality
 */

/**
 * Translation helper - gets translation from window.TRANSLATIONS with fallback
 */
function _t(key, fallback) {
    return (window.TRANSLATIONS && window.TRANSLATIONS[key]) || fallback || key;
}

/**
 * Validate phone number based on country prefix
 * @param {string} phoneNumber - The phone number without prefix
 * @param {string} prefix - The country prefix (+373, +40, +380)
 * @returns {object} - { valid: boolean, error: string }
 */
function validatePhoneNumber(phoneNumber, prefix) {
    // Remove all non-digit characters
    const cleanPhone = phoneNumber.replace(/\D/g, '');
    
    if (!cleanPhone) {
        return { valid: false, error: _t('error_phone_required', 'Numărul de telefon este obligatoriu') };
    }
    
    // Validation rules per country
    const rules = {
        '+373': { // Moldova
            length: 8,
            pattern: /^[67]\d{7}$/,
            example: '79123456',
            error: _t('error_phone_md', 'Pentru Moldova introduceți 8 cifre (ex: 79123456)')
        },
        '+40': { // Romania
            length: 9,
            pattern: /^7\d{8}$/,
            example: '721234567',
            error: _t('error_phone_ro', 'Pentru România introduceți 9 cifre (ex: 721234567)')
        },
        '+380': { // Ukraine
            length: 9,
            pattern: /^[3-9]\d{8}$/,
            example: '501234567',
            error: _t('error_phone_ua', 'Pentru Ucraina introduceți 9 cifre (ex: 501234567)')
        }
    };
    
    const rule = rules[prefix];
    if (!rule) {
        // Default validation - at least 7 digits
        if (cleanPhone.length < 7) {
            return { valid: false, error: _t('error_phone_invalid', 'Introduceți un număr de telefon valid') };
        }
        return { valid: true, error: '' };
    }
    
    // Check length
    if (cleanPhone.length !== rule.length) {
        return { valid: false, error: rule.error };
    }
    
    // Check pattern
    if (!rule.pattern.test(cleanPhone)) {
        return { valid: false, error: rule.error };
    }
    
    return { valid: true, error: '' };
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initMobileMenu();
    initStickyHeader();
    initFAQAccordion();
    initScrollAnimations();
    initSmoothScroll();
    initContactForm();
    initConsultationForm();
    initReviewCounters();
    initCounters();
    initViberInlineSVG();
    initWhatsAppCard();
    initPhonePrefixSelector();
    initLanguageSelector();
});

/**
 * Fetch official Viber SVG from Simple Icons CDN and inject inline into #viberFloatBtn
 */
function initViberInlineSVG() {
    const btn = document.getElementById('viberFloatBtn');
    if (!btn) return;
    const svgUrl = 'https://cdn.simpleicons.org/viber/ffffff';
    fetch(svgUrl).then(r => {
        if (!r.ok) throw new Error('Failed to fetch SVG');
        return r.text();
    }).then(svgText => {
        // Remove any XML prolog and ensure svg fits
        let cleaned = svgText.replace(/^[^<]*/, '').trim();
        // Ensure width/height attributes are suitable for styling
        cleaned = cleaned.replace(/width="[^"]+"/g, 'width="30"');
        cleaned = cleaned.replace(/height="[^"]+"/g, 'height="30"');
        btn.innerHTML = cleaned;
    }).catch(err => {
        console.log('initViberInlineSVG error:', err);
    });
}

/**
 * Mobile Menu Toggle
 */
function initMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const nav = document.getElementById('nav');
    const navLinks = document.querySelectorAll('.nav-link');

    if (!mobileMenuBtn || !nav) return;

    mobileMenuBtn.addEventListener('click', function() {
        this.classList.toggle('active');
        nav.classList.toggle('active');
        document.body.style.overflow = nav.classList.contains('active') ? 'hidden' : '';
    });

    // Close menu when clicking a link
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenuBtn.classList.remove('active');
            nav.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!nav.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
            mobileMenuBtn.classList.remove('active');
            nav.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

/**
 * Sticky Header on Scroll
 */
function initStickyHeader() {
    const header = document.getElementById('header');
    if (!header) return;

    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });
}

/**
 * FAQ Accordion
 */
function initFAQAccordion() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        if (!question) return;

        question.addEventListener('click', function() {
            const isActive = item.classList.contains('active');

            // Close all FAQ items
            faqItems.forEach(faq => {
                faq.classList.remove('active');
            });

            // Open clicked item if it wasn't active
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });
}

/**
 * Scroll Animations with Intersection Observer
 */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.fade-in, .fade-in-left, .fade-in-right');

    if (!animatedElements.length) return;

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

/**
 * Animated Counters when they enter viewport
 */
function initCounters() {
    const counters = document.querySelectorAll('.counter-number');
    if (!counters.length) {
        console.log('initCounters: no .counter-number elements found');
        return;
    }

    console.log('initCounters: found', counters.length, 'counters');

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -10% 0px',
        threshold: 0.1
    };

    const animate = (el, target) => {
        const duration = 1800;
        const start = 0;
        const startTime = performance.now();

        const step = (now) => {
            const progress = Math.min((now - startTime) / duration, 1);
            const current = Math.floor(progress * (target - start) + start);
            el.textContent = current;
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = target.toString();
            }
        };

        requestAnimationFrame(step);
    };

    const observer = (window.IntersectionObserver) ? new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const raw = el.getAttribute('data-target') || el.textContent || '0';
                const num = parseFloat(String(raw).replace(/[^0-9.\-]/g, '')) || 0;
                console.log('initCounters: animating element, data-target="' + raw + '", parsed=' + num);
                if (num > 0) animate(el, Math.round(num));
                else el.textContent = String(Math.round(num));
                obs.unobserve(el);
            }
        });
    }, observerOptions) : null;

    counters.forEach(c => {
        const raw = c.getAttribute('data-target') || c.textContent || '';
        const parsed = parseFloat(String(raw).replace(/[^0-9.\-]/g, '')) || 0;
        console.log('initCounters: element', c, 'data-target="' + raw + '", parsed=' + parsed);
        c.textContent = '0';
        if (observer) {
            observer.observe(c);
        } else {
            // Fallback: animate immediately if IntersectionObserver not available
            if (parsed > 0) animate(c, Math.round(parsed));
            else c.textContent = String(Math.round(parsed));
        }
    });
}

/**
 * Smooth Scroll for Anchor Links
 */
function initSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                
                const headerHeight = document.getElementById('header')?.offsetHeight || 80;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Phone Prefix Selector - Changes placeholder based on country
 */
function initPhonePrefixSelector() {
    const phoneFormats = {
        '+373': { placeholder: '69 123 456', pattern: '## ### ###', digits: 8 },      // Moldova
        '+40': { placeholder: '712 345 678', pattern: '### ### ###', digits: 9 },     // Romania
        '+380': { placeholder: '67 123 4567', pattern: '## ### ####', digits: 9 }     // Ukraine
    };

    // Find all phone prefix selectors
    const prefixSelectors = document.querySelectorAll('.phone-prefix-select');
    
    prefixSelectors.forEach(select => {
        const phoneInput = select.closest('.phone-input-group').querySelector('.phone-number-input');
        if (!phoneInput) return;

        // Update placeholder on change
        select.addEventListener('change', function() {
            const format = phoneFormats[this.value];
            if (format) {
                phoneInput.placeholder = format.placeholder;
                phoneInput.value = ''; // Clear value when changing country
            }
            // Clear any error when changing country
            const errorEl = phoneInput.closest('.form-group').querySelector('.form-error');
            if (errorEl) {
                errorEl.textContent = '';
                errorEl.classList.remove('show');
            }
        });

        // Set initial placeholder
        const initialFormat = phoneFormats[select.value];
        if (initialFormat) {
            phoneInput.placeholder = initialFormat.placeholder;
        }
        
        // Restrict input to digits only and limit length
        phoneInput.addEventListener('input', function(e) {
            // Remove non-digits
            let value = this.value.replace(/\D/g, '');
            
            // Limit to max digits based on selected country
            const format = phoneFormats[select.value];
            const maxDigits = format ? format.digits : 10;
            if (value.length > maxDigits) {
                value = value.slice(0, maxDigits);
            }
            
            this.value = value;
        });
        
        // Validate on blur (when user leaves the field)
        phoneInput.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value === '') return; // Don't validate empty on blur
            
            const validation = validatePhoneNumber(value, select.value);
            const errorEl = this.closest('.form-group').querySelector('.form-error');
            
            if (!validation.valid && errorEl) {
                errorEl.textContent = validation.error;
                errorEl.classList.add('show');
            } else if (errorEl) {
                errorEl.textContent = '';
                errorEl.classList.remove('show');
            }
        });
    });
}

/**
 * Contact Form Handling
 */
function initContactForm() {
    const form = document.getElementById('contactForm');
    
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        form.querySelectorAll('.form-error').forEach(el => {
            el.textContent = '';
            el.classList.remove('show');
        });

        // Get form data
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        // Validation
        let hasErrors = false;

        if (!data.nume || data.nume.trim() === '') {
            showFieldError('contact-nume', _t('error_name_required', 'Numele este obligatoriu'));
            hasErrors = true;
        }

        if (!data.serviciu || data.serviciu.trim() === '') {
            showFieldError('contact-serviciu', _t('error_service_required', 'Selectați un serviciu'));
            hasErrors = true;
        }

        // Get phone prefix
        const phonePrefix = data.phone_prefix || '+373';
        const phoneValidation = validatePhoneNumber(data.telefon || '', phonePrefix);
        
        if (!phoneValidation.valid) {
            showFieldError('contact-telefon', phoneValidation.error);
            hasErrors = true;
        }

        // Check reCAPTCHA
        const recaptchaResponse = data['g-recaptcha-response'];
        if (!recaptchaResponse || recaptchaResponse.trim() === '') {
            showFieldError('contact-recaptcha', _t('error_captcha', 'Completați verificarea anti-spam'));
            hasErrors = true;
        }

        if (hasErrors) return;

        // Submit to server endpoint
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = _t('form_sending', 'Se trimite...');

        fetch('send_contact.php', {
            method: 'POST',
            body: formData
        }).then(r => r.json()).then(json => {
            if (json.success) {
                showFormMessage(json.message || _t('form_success', 'Mesaj trimis cu succes! Vă vom contacta în curând.'), 'success');
                form.reset();
                // Reset reCAPTCHA
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
            } else {
                showFormMessage(json.error || _t('form_error', 'Eroare la trimitere. Încercați din nou.'), 'error');
            }
        }).catch(err => {
            console.error('Contact send error', err);
            showFormMessage(_t('form_network_error', 'Eroare de rețea. Încercați din nou.'), 'error');
        }).finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
}

function showFieldError(fieldName, message) {
    const errorEl = document.getElementById('error-' + fieldName);
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.add('show');
    }
}

/**
 * Show Form Message
 */
function showFormMessage(message, type) {
    // Remove existing messages
    const existingMessage = document.querySelector('.form-message');
    if (existingMessage) {
        existingMessage.remove();
    }

    // Create message element
    const messageEl = document.createElement('div');
    messageEl.className = `form-message form-message--${type}`;
    messageEl.textContent = message;
    
    // Style the message
    messageEl.style.cssText = `
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        font-weight: 500;
        text-align: center;
        animation: fadeIn 0.3s ease;
    `;

    if (type === 'success') {
        messageEl.style.backgroundColor = '#d4edda';
        messageEl.style.color = '#155724';
        messageEl.style.border = '1px solid #c3e6cb';
    } else {
        messageEl.style.backgroundColor = '#f8d7da';
        messageEl.style.color = '#721c24';
        messageEl.style.border = '1px solid #f5c6cb';
    }

    // Insert message
    const form = document.getElementById('contactForm');
    form.insertBefore(messageEl, form.firstChild);

    // Remove message after 5 seconds for success
    if (type === 'success') {
        setTimeout(() => {
            messageEl.style.opacity = '0';
            messageEl.style.transition = 'opacity 0.3s ease';
            setTimeout(() => messageEl.remove(), 300);
        }, 5000);
    }
}

/**
 * Add CSS for form message animation
 */
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(styleSheet);

/**
 * Consultation Form (embedded in hero)
 */
function initConsultationForm() {
    const form = document.getElementById('consultationForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        form.querySelectorAll('.form-error.show').forEach(el => {
            el.classList.remove('show');
        });

        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        const errors = validateConsultationForm(data);
        if (Object.keys(errors).length > 0) {
            Object.keys(errors).forEach(field => {
                const errorEl = document.getElementById(`error-${field}`);
                if (errorEl) {
                    errorEl.textContent = errors[field];
                    errorEl.classList.add('show');
                }
            });
            return;
        }

        submitConsultationForm(data, form);
    });
}

/**
 * Validate consultation form
 */
function validateConsultationForm(data) {
    const errors = {};

    if (!data.nume || data.nume.trim() === '') {
        errors.nume = _t('error_name_required', 'Numele este obligatoriu');
    }

    if (!data.serviciu || data.serviciu.trim() === '') {
        errors.serviciu = _t('error_service_required', 'Selectați un serviciu');
    }

    // Get phone prefix
    const phonePrefix = data.phone_prefix || '+373';
    const phoneValidation = validatePhoneNumber(data.telefon || '', phonePrefix);
    
    if (!phoneValidation.valid) {
        errors.telefon = phoneValidation.error;
    }

    // Check reCAPTCHA response
    const recaptchaResponse = data['g-recaptcha-response'];
    if (!recaptchaResponse || recaptchaResponse.trim() === '') {
        errors.recaptcha = _t('error_captcha', 'Completați verificarea anti-spam');
    }

    return errors;
}

/**
 * Submit consultation form
 */
function submitConsultationForm(data, form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    submitBtn.disabled = true;
    submitBtn.textContent = _t('form_sending', 'Se trimite...');

    // Prepare data for submission - use form directly to ensure recaptcha_token is included
    const formData = new FormData(form);

    fetch('send_contact.php', {
        method: 'POST',
        body: formData
    }).then(r => r.json()).then(json => {
        if (json.success) {
            // Clear form
            form.reset();
            
            // Show success message
            const msgEl = document.createElement('div');
            msgEl.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                font-weight: 500;
                z-index: 2000;
                animation: slideDown 0.3s ease;
            `;
            msgEl.textContent = _t('form_success_consultation', 'Mulțumim! Vă vom contacta în maxim 24 de ore.');
            document.body.appendChild(msgEl);

            setTimeout(() => {
                msgEl.style.animation = 'slideUp 0.3s ease';
                setTimeout(() => msgEl.remove(), 300);
            }, 2000);
        } else {
            // Show error
            const msgEl = document.createElement('div');
            msgEl.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                font-weight: 500;
                z-index: 2000;
                animation: slideDown 0.3s ease;
            `;
            msgEl.textContent = json.error || _t('form_error', 'Eroare la trimitere. Încercați din nou.');
            document.body.appendChild(msgEl);

            setTimeout(() => {
                msgEl.style.animation = 'slideUp 0.3s ease';
                setTimeout(() => msgEl.remove(), 300);
            }, 3000);
        }
    }).catch(err => {
        console.error('Consultation form submission error', err);
        const msgEl = document.createElement('div');
        msgEl.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            z-index: 2000;
            animation: slideDown 0.3s ease;
        `;
        msgEl.textContent = _t('form_network_error', 'Eroare de rețea. Încercați din nou.');
        document.body.appendChild(msgEl);

        setTimeout(() => {
            msgEl.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => msgEl.remove(), 300);
        }, 3000);
    }).finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        // Reset reCAPTCHA widget for next attempt
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.reset();
        }
    });
}

/**
 * Review form character counters (for review-title and review-message)
 */
function initReviewCounters() {
    const title = document.getElementById('review-title');
    const message = document.getElementById('review-message');
    const titleCount = document.getElementById('title-count');
    const messageCount = document.getElementById('message-count');

    console.log('initReviewCounters: found title=', !!title, 'message=', !!message, 'titleCount=', !!titleCount, 'messageCount=', !!messageCount);
    if (!title && !message) {
        // fallback: listen for inputs at document level in case form is injected later
        document.addEventListener('input', function onDocInput(e){
            if (e.target && (e.target.id === 'review-title' || e.target.id === 'review-message')) {
                document.removeEventListener('input', onDocInput);
                initReviewCounters();
            }
        });
        return;
    }

    const update = () => {
        if (title && titleCount) titleCount.textContent = String(title.value.length);
        if (message && messageCount) messageCount.textContent = String(message.value.length);
    };

    if (title) {
        title.addEventListener('input', update);
        title.addEventListener('change', update);
        title.addEventListener('keyup', update);
    }
    if (message) {
        message.addEventListener('input', update);
        message.addEventListener('change', update);
        message.addEventListener('keyup', update);
    }

    // Initialize counts on load
    update();
    console.log('initReviewCounters: initialized counts', titleCount?.textContent, messageCount?.textContent);
}

/**
 * Lazy Loading Images (native browser support enhancement)
 */
document.querySelectorAll('img').forEach(img => {
    if (!img.hasAttribute('loading')) {
        img.setAttribute('loading', 'lazy');
    }
});

/**
 * WhatsApp floating card/modal handler
 */
function initWhatsAppCard() {
    const btn = document.getElementById('whatsappFloatBtn');
    const card = document.getElementById('whatsappCard');
    if (!btn || !card) return;

    const closeBtn = card.querySelector('.whatsapp-card-close');

    const openCard = () => {
        card.classList.add('open');
        card.setAttribute('aria-hidden', 'false');
        btn.setAttribute('aria-expanded', 'true');
        // ensure page scrolling is allowed (clear any overflow hidden set elsewhere)
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        // small timeout to allow event listeners to attach after click
        setTimeout(() => document.addEventListener('click', outsideClickListener), 0);
    };

    const closeCard = () => {
        card.classList.remove('open');
        card.setAttribute('aria-hidden', 'true');
        btn.setAttribute('aria-expanded', 'false');
        // ensure any overflow hidden is cleared when closing
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        document.removeEventListener('click', outsideClickListener);
    };

    const outsideClickListener = (e) => {
        if (!card.contains(e.target) && !btn.contains(e.target)) {
            closeCard();
        }
    };

    btn.addEventListener('click', (e) => {
        // If the floating element is an anchor link, allow default navigation to proceed
        if (btn.tagName && btn.tagName.toLowerCase() === 'a') return;
        e.preventDefault();
        if (card.classList.contains('open')) closeCard(); else openCard();
    });

    closeBtn && closeBtn.addEventListener('click', (e) => { e.preventDefault(); closeCard(); });

    // Close when pressing Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && card.classList.contains('open')) {
            closeCard();
        }
    });

    // Close after clicking a contact (link will open in new tab)
    card.querySelectorAll('.whatsapp-contacts a').forEach(a => {
        a.addEventListener('click', () => {
            closeCard();
        });
    });
}

/**
 * Show a small toast/card indicating the review was sent and is awaiting confirmation
 */
function showReviewSentCard(message, timeoutMs = 4500) {
    if (!message) message = 'Recenzia a fost trimisă și așteaptă confirmare.';
    // Avoid duplicates
    let existing = document.getElementById('review-sent-card');
    if (existing) {
        existing.textContent = message;
        existing.classList.remove('hide');
        clearTimeout(existing._hideTimer);
        existing._hideTimer = setTimeout(()=>{
            existing.classList.add('hide');
            setTimeout(()=> existing.remove(), 350);
        }, timeoutMs);
        return;
    }

    const card = document.createElement('div');
    card.id = 'review-sent-card';
    card.className = 'review-sent-card';
    card.textContent = message;

    // Basic styles (kept simple so they don't require CSS file edits)
    card.style.cssText = '\
        position: fixed; top: 20px; right: 20px; z-index: 99999; max-width: 320px;\
        background: #ffffff; color:#0b2b3b; border:1px solid rgba(11,43,59,0.08);\
        box-shadow: 0 8px 24px rgba(11,43,59,0.08); padding:14px 16px;\
        border-radius:10px; font-weight:600; font-size:14px; opacity:0; transform:translateY(-6px);\
        transition: opacity 0.25s ease, transform 0.25s ease;\
    ';

    document.body.appendChild(card);

    // Allow CSS transition to run
    requestAnimationFrame(()=>{
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    });

    // Hide after timeout
    card._hideTimer = setTimeout(()=>{
        card.classList.add('hide');
        card.style.opacity = '0';
        card.style.transform = 'translateY(-6px)';
        setTimeout(()=> card.remove(), 350);
    }, timeoutMs);
}

// Expose globally so inline scripts can call it
window.showReviewSentCard = showReviewSentCard;

/**
 * Service Detail Modal Functions
 */
let currentSelectedService = null;

function openServiceModal(service) {
    // Store the current service for later use
    currentSelectedService = service;
    
    const modal = document.getElementById('serviceDetailModal');
    const overlay = document.getElementById('serviceDetailOverlay');
    const imageContainer = document.getElementById('serviceModalImage');
    const titleEl = document.getElementById('serviceModalTitle');
    const descriptionEl = document.getElementById('serviceModalDescription');
    const featuresEl = document.getElementById('serviceModalFeatures');
    
    // Set title
    titleEl.textContent = service.title || '';
    
    // Set description (use full_description if available, otherwise short_description)
    descriptionEl.textContent = service.full_description || service.short_description || '';
    
    const ot = service && service.offers_transport;
    const offersTransport = ot === true || ot === 1 || ot === '1' || Number(ot) === 1;
    const transportLabel = (window.TRANSLATIONS && window.TRANSLATIONS.services_offers_transport) || 'Se oferă transport';
    
    // Set image
    if (service.image_url) {
        imageContainer.innerHTML = `<img src="${service.image_url}" alt="${service.title}">`;
    } else if (service.icon_svg) {
        imageContainer.innerHTML = service.icon_svg;
    } else {
        imageContainer.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>`;
    }
    
    // Set features (transport line last, when enabled)
    featuresEl.innerHTML = '';
    const lines = [];
    if (service.features) {
        let features = service.features;
        if (typeof features === 'string') {
            try {
                features = JSON.parse(features);
            } catch (e) {
                features = features.split('\n').filter(f => String(f).trim());
            }
        }
        if (Array.isArray(features)) {
            features.forEach(feature => {
                const t = String(feature).trim();
                if (t) {
                    lines.push(t);
                }
            });
        }
    }
    if (offersTransport) {
        lines.push(transportLabel);
    }
    lines.forEach(text => {
        const li = document.createElement('li');
        li.textContent = text;
        featuresEl.appendChild(li);
    });
    
    // Show modal
    modal.classList.add('active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeServiceDetailModal() {
    const modal = document.getElementById('serviceDetailModal');
    const overlay = document.getElementById('serviceDetailOverlay');
    
    modal.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

/**
 * Request selected service - scrolls to hero consultation form with service pre-selected
 */
function requestSelectedService() {
    closeServiceDetailModal();

    const panel = document.getElementById('consultationFormPanel');
    if (panel) {
        panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Pre-select the service in the custom dropdown if we have a selected service
    if (currentSelectedService) {
        const hiddenInput = document.getElementById('consultation-serviciu');
        const selector = document.getElementById('consultationServiceSelector');
        
        if (hiddenInput && selector) {
            // Use title_original if available (lowercase original title), otherwise lowercase the title
            const serviceValue = (currentSelectedService.title_original || currentSelectedService.title).toLowerCase();
            
            // Find the matching option in the dropdown
            const options = selector.querySelectorAll('.service-option');
            const currentSpan = selector.querySelector('.service-current');
            
            options.forEach(function(option) {
                const optValue = option.getAttribute('data-value');
                if (optValue && optValue.toLowerCase() === serviceValue) {
                    // Set hidden input value
                    hiddenInput.value = optValue;
                    
                    // Update visible text
                    if (currentSpan) {
                        currentSpan.textContent = option.textContent.trim();
                        currentSpan.classList.remove('placeholder');
                    }
                    
                    // Mark as selected
                    options.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');
                }
            });
        }
    }
}

/**
 * Toggle show all services
 */
function toggleAllServices() {
    const grid = document.getElementById('servicesGrid');
    const container = document.getElementById('servicesScrollContainer');
    const btn = document.getElementById('showMoreServices');
    const showMoreText = btn.querySelector('.show-more-text');
    const showLessText = btn.querySelector('.show-less-text');
    
    const isExpanded = grid.classList.contains('services-expanded');
    
    if (isExpanded) {
        // Collapse
        grid.classList.remove('services-expanded');
        container.classList.remove('scrollable');
        showMoreText.style.display = '';
        showLessText.style.display = 'none';
        
        // Scroll to services section top
        document.getElementById('servicii').scrollIntoView({ behavior: 'smooth' });
    } else {
        // Expand
        grid.classList.add('services-expanded');
        container.classList.add('scrollable');
        showMoreText.style.display = 'none';
        showLessText.style.display = '';
    }
}

// Close service modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('serviceDetailModal');
        if (modal && modal.classList.contains('active')) {
            closeServiceDetailModal();
        }
    }
});

/**
 * Language Selector Toggle
 */
function initLanguageSelector() {
    const langBtn = document.getElementById('langBtn');
    const langDropdown = document.getElementById('langDropdown');
    
    if (!langBtn || !langDropdown) return;
    
    // Toggle dropdown on button click
    langBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        langDropdown.classList.toggle('active');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!langBtn.contains(e.target) && !langDropdown.contains(e.target)) {
            langDropdown.classList.remove('active');
        }
    });
    
    // Close dropdown on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            langDropdown.classList.remove('active');
        }
    });
}

// Expose functions globally
window.openServiceModal = openServiceModal;
window.closeServiceDetailModal = closeServiceDetailModal;
window.requestSelectedService = requestSelectedService;
window.toggleAllServices = toggleAllServices;