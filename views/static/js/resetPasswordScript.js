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
    // Validación simple y suficiente para front
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function setLoading(btn, loading, txtLoading = 'Enviando...') {
    if (!btn) return;
    btn.disabled = loading;
    btn.dataset._label ??= btn.value || btn.textContent;
    if (btn.tagName === 'INPUT') {
        btn.value = loading ? txtLoading : btn.dataset._label;
    } else {
        btn.textContent = loading ? txtLoading : btn.dataset._label;
    }
}

// Elementos Step 1
const step1 = document.getElementById('step1');
const formStep1 = step1.querySelector('form');
const emailInput = document.getElementById('email');
const sendCodeBtn = document.getElementById('sendCodeBtn');

// Elementos Step 2
const step2 = document.getElementById('step2');
const formStep2 = step2.querySelector('form');
const codeInputs = Array.from(step2.querySelectorAll('.campoCode'));
const recoverBtn = document.getElementById('recoverBtn');

// Empieza mostrando el paso 1
showStep(1);

// Submit paso 1: validar email y pasar a paso 2
formStep1.addEventListener('submit', async (e) => {
    e.preventDefault();
    setError(step1, '');

    const email = (emailInput.value || '').trim();
    if (!isValidEmail(email)) {
        setError(step1, 'Ingresa un correo válido.');
        emailInput.focus();
        return;
    }

    // (Opcional) Guarda el email para usarlo luego
    sessionStorage.setItem('recover_email', email);

    // Si vas a pegarle a tu backend, descomenta este bloque y ajusta la URL:
    /*
    try {
      setLoading(sendCodeBtn, true);
      const resp = await fetch('/api/auth/send-reset-code.php', {
        method: 'POST',
        body: new URLSearchParams({ email })
      });
      if (!resp.ok) throw new Error('No se pudo enviar el código.');
      const data = await resp.json(); // si devuelves JSON
      if (!data.ok) throw new Error(data.message || 'Error enviando código.');
    } catch (err) {
      setError(step1, err.message || 'Ocurrió un error enviando el código.');
      setLoading(sendCodeBtn, false);
      return;
    } finally {
      setLoading(sendCodeBtn, false);
    }
    */

    // Cambia al paso 2
    showStep(2);
    // Limpia y enfoca el primer input del código
    codeInputs.forEach(i => i.value = '');
    codeInputs[0]?.focus();
});

// Auto-avance entre inputs del código (por si no usas saltoCampo.js)
codeInputs.forEach((input, idx) => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/\D/g, '').slice(0, 1);
        if (input.value && idx < codeInputs.length - 1) {
            codeInputs[idx + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && idx > 0) {
            codeInputs[idx - 1].focus();
        }
    });
});

// Submit paso 2: juntar código (6 dígitos) y seguir tu flujo
formStep2.addEventListener('submit', async (e) => {
    e.preventDefault();
    setError(step2, '');

    const code = codeInputs.map(i => i.value).join('');
    if (code.length !== 6) {
        setError(step2, 'El código debe tener 6 dígitos.');
        (codeInputs.find(i => !i.value) || codeInputs[0]).focus();
        return;
    }

    const email = sessionStorage.getItem('recover_email') || '';

    // Aquí validarías el código en tu backend:
    /*
    try {
      setLoading(recoverBtn, true, 'Validando...');
      const resp = await fetch('/api/auth/verify-reset-code.php', {
        method: 'POST',
        body: new URLSearchParams({ email, code })
      });
      if (!resp.ok) throw new Error('No se pudo validar el código.');
      const data = await resp.json();
      if (!data.ok) throw new Error(data.message || 'Código inválido.');
      // Redirigir a pantalla para nueva contraseña, por ejemplo:
      window.location.href = '/nueva-contrasena';
    } catch (err) {
      setError(step2, err.message || 'Ocurrió un error validando el código.');
    } finally {
      setLoading(recoverBtn, false);
    }
    */

    // Si aún no tienes backend, simulamos OK:
    alert(`Código ${code} aceptado para ${email} (simulado).`);
});
