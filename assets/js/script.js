function toast(message, type = 'success') {
  let box = document.getElementById('globalToast');
  if (!box) {
    box = document.createElement('div');
    box.id = 'globalToast';
    box.className = 'toast';
    document.body.appendChild(box);
  }
  box.textContent = message;
  box.className = 'toast show ' + type;
  clearTimeout(window.__toast);
  window.__toast = setTimeout(() => box.classList.remove('show'), 3200);
}

function toggleNav() {
  const nav = document.getElementById('mainNav') || document.querySelector('.main-nav');
  if (nav) nav.classList.toggle('open');
}

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.main-nav');
  if (btn && nav) {
    btn.onclick = () => nav.classList.toggle('open');
  }
  document.querySelectorAll('input[type=date]').forEach(i => {
    if (!i.min) i.min = new Date().toISOString().split('T')[0];
  });
});

// Resilient API/session helpers used by customer & admin portals
async function vsJsonFetch(url, options = {}, loginUrl = 'login.php') {
  const response = await fetch(url, { credentials: 'include', ...options });
  let data = { success: false, message: 'Unexpected server response.' };
  try {
    data = await response.json();
  } catch (e) {}
  if (response.status === 401) {
    window.location.href = loginUrl;
    throw new Error('Session expired');
  }
  return data;
}

function formatINR(value) {
  return '₹' + Number(value || 0).toLocaleString('en-IN');
}

function safeText(value, fallback = '—') {
  return value === undefined || value === null || value === '' ? fallback : String(value);
}
