// CivicMind X - Main Application JavaScript Framework

document.addEventListener('DOMContentLoaded', () => {
    initThemeToggler();
    initVoiceSynthesis();
    initSpeechRecognition();
});

// 1. Theme Switcher (Dark/Light)
function initThemeToggler() {
    const themeBtn = document.getElementById('theme-toggle-btn');
    if (!themeBtn) return;

    const currentTheme = localStorage.getItem('civicmind_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);

    themeBtn.addEventListener('click', () => {
        const theme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('civicmind_theme', theme);
        updateThemeIcon(theme);
    });
}

function updateThemeIcon(theme) {
    const icon = document.getElementById('theme-icon');
    if (!icon) return;
    icon.className = theme === 'dark' ? 'bi bi-sun-fill text-warning' : 'bi bi-moon-stars-fill text-primary';
}

// 2. Multilingual Voice Output (SpeechSynthesis)
function speakText(text, lang = 'en') {
    if (!('speechSynthesis' in window)) {
        console.warn('Speech synthesis not supported on this browser.');
        return;
    }
    window.speechSynthesis.cancel(); // Stop ongoing speech
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 0.95;
    utterance.lang = lang === 'ta' ? 'ta-IN' : (lang === 'hi' ? 'hi-IN' : 'en-US');
    window.speechSynthesis.speak(utterance);
}

// 3. Multilingual Speech Recognition (Voice Input)
function initSpeechRecognition() {
    const micBtn = document.getElementById('voice-input-btn');
    const promptInput = document.getElementById('ai-prompt-input');
    if (!micBtn || !promptInput) return;

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        micBtn.style.display = 'none';
        return;
    }

    const recognition = new SpeechRecognition();
    recognition.continuous = false;
    recognition.interimResults = false;

    micBtn.addEventListener('click', () => {
        const activeLang = localStorage.getItem('civicmind_lang') || 'en';
        recognition.lang = activeLang === 'ta' ? 'ta-IN' : (activeLang === 'hi' ? 'hi-IN' : 'en-US');
        micBtn.classList.add('btn-danger', 'pulse-target');
        recognition.start();
    });

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        promptInput.value = transcript;
        micBtn.classList.remove('btn-danger', 'pulse-target');
    };

    recognition.onerror = () => {
        micBtn.classList.remove('btn-danger', 'pulse-target');
    };

    recognition.onend = () => {
        micBtn.classList.remove('btn-danger', 'pulse-target');
    };
}

// 4. Dynamic Toast Notifications
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position:fixed; bottom:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:10px;';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `glass-panel p-3 text-white d-flex align-items-center justify-content-between shadow-lg`;
    toast.style.cssText = 'min-width: 300px; max-width: 450px; border-left: 4px solid ' + (type === 'success' ? '#10b981' : '#f43f5e');

    toast.innerHTML = `
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-${type === 'success' ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-danger'} fs-5"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="btn-close btn-close-white ms-3" onclick="this.parentElement.remove()"></button>
    `;

    container.appendChild(toast);
    setTimeout(() => {
        if (toast.parentElement) toast.remove();
    }, 4500);
}
