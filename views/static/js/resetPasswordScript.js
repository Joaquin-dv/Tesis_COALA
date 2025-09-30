// resetPasswordScript.js

// Utilidades
function showStep(stepToShow) {
    const steps = [document.getElementById('step1'), document.getElementById('step2')];
    steps.forEach((s, i) => s.classList.toggle('hidden', i !== stepToShow - 1));
}

function setError(container, msg) {
    const box = container.querySelector('.errorMsg');
    if (!box) return;
    box.textContent = msg || '';
    box.style.display = msg ? 'block' : 'none';
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

const step1 = document.getElementById('step1');
const formStep1 = step1.querySelector('form');
const emailInput = document.getElementById('email');
const sendCodeBtn = document.getElementById('sendCodeBtn');


const step2 = document.getElementById('step2');
const formStep2 = step2.querySelector('form');
const codeInputs = Array.from(step2.querySelectorAll('.campoCode'));
const recoverBtn = document.getElementById('recoverBtn');


showStep(1);

formStep1.addEventListener('submit', async (e) => {
    e.preventDefault();
    setError(step1, '');

    const email = (emailInput.value || '').trim();
    if (!isValidEmail(email)) {
        setError(step1, 'Ingresa un correo válido.');
        emailInput.focus();
        return;
    }
    showStep(2);
    
    codeInputs.forEach(i => i.value = '');
    codeInputs[0]?.focus();
});

formStep2.addEventListener('submit', async (e) => {
    e.preventDefault();
    setError(step2, '');

    const code = codeInputs.map(i => i.value).join('');
    if (code.length !== 6) {
        setError(step2, 'El código debe tener 6 dígitos.');
        (codeInputs.find(i => !i.value) || codeInputs[0]).focus();
        return;
    }
});
