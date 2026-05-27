// ============================================================
// GameTopup Marketplace — Clean Premium UI Interactions
// Handles only animations, transitions, and micro-interactivity
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Lucide Icons
    if (window.lucide) {
        window.lucide.createIcons();
    }

    // 2. Universal Countdown Timer Ticking Interval
    const countdownContainer = document.getElementById('countdown-timer-container');
    const hourBox = document.getElementById('cd-hours');
    const minBox = document.getElementById('cd-minutes');
    const secBox = document.getElementById('cd-seconds');
 
    if (hourBox && minBox && secBox) {
        let totalSeconds = 3 * 3600 + 45 * 60 + 20; // default fallback
        
        if (countdownContainer && countdownContainer.getAttribute('data-end')) {
            const endTimeStr = countdownContainer.getAttribute('data-end');
            const endTime = new Date(endTimeStr.getTime ? endTimeStr : endTimeStr.replace(/-/g, '/')).getTime();
            const now = new Date().getTime();
            const diff = Math.floor((endTime - now) / 1000);
            totalSeconds = diff > 0 ? diff : 0;
        }

        const timer = setInterval(() => {
            if (totalSeconds > 0) {
                totalSeconds--;
                const hrs = Math.floor(totalSeconds / 3600);
                const mins = Math.floor((totalSeconds % 3600) / 60);
                const secs = totalSeconds % 60;
 
                hourBox.textContent = String(hrs).padStart(2, '0');
                minBox.textContent = String(mins).padStart(2, '0');
                secBox.textContent = String(secs).padStart(2, '0');
            } else {
                hourBox.textContent = '00';
                minBox.textContent = '00';
                secBox.textContent = '00';
                clearInterval(timer);
            }
        }, 1000);
    }
});

// 3. Retro Arcade Toast Notification System
function showToast(message) {
    // Remove existing toast if any
    const prevToast = document.querySelector('.floating-toast-alert');
    if (prevToast) prevToast.remove();

    const toast = document.createElement('div');
    toast.className = 'floating-toast-alert fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-[#0c0f1d] border-2 border-[#ff007f] px-5 py-4 rounded-2xl shadow-[0_12px_0px_#0f172a,0_20px_40px_rgba(255,0,127,0.3)] text-white select-none pointer-events-none';
    toast.style.fontFamily = 'system-ui, sans-serif';
    toast.style.boxShadow = '0 12px 24px rgba(255, 0, 127, 0.15)';
    toast.innerHTML = `
      <div style="height:1.5rem;width:1.5rem;border-radius:0.5rem;background:rgba(255,0,127,0.1);display:flex;align-items:center;justify-content:center;border:1px solid #ff007f;color:#ff007f;font-weight:900;font-size:0.75rem;flex-shrink:0">★</div>
      <p style="font-size:0.75rem;font-weight:900;margin:0;color:#f1f5f9">${message}</p>
    `;

    // Spring entry animation
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(30px) scale(0.85)';
    toast.style.transition = 'opacity 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';

    document.body.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0) scale(1)';
    });

    // Auto remove in 2.5s
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px) scale(0.9)';
        setTimeout(() => toast.remove(), 400);
    }, 2500);
}

// 4. Clipboard Copy Helper (with spring visual feedback)
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Berhasil disalin ke clipboard! 📋');
        if (btn) {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = `<i data-lucide="check" class="h-3.5 w-3.5 mr-1 align-middle text-emerald-500"></i> Tersalin`;
            btn.style.color = '#10b981';
            btn.style.borderColor = '#10b981';
            if (window.lucide) window.lucide.createIcons();
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.color = '';
                btn.style.borderColor = '';
                if (window.lucide) window.lucide.createIcons();
            }, 2000);
        }
    });
}
