// Validações básicas no lado do cliente (feedback imediato ao usuário).
document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('form');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var camposObrigatorios = form.querySelectorAll('[required]');
            var valido = true;

            camposObrigatorios.forEach(function (campo) {
                if (!campo.value.trim()) {
                    valido = false;
                    campo.classList.add('input-erro');
                } else {
                    campo.classList.remove('input-erro');
                }
            });

            var campoEmail = form.querySelector('input[type="email"]');
            if (campoEmail && campoEmail.value.trim()) {
                var emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(campoEmail.value.trim());
                if (!emailValido) {
                    valido = false;
                    campoEmail.classList.add('input-erro');
                }
            }

            var campoTelefone = form.querySelector('#telefone');
            if (campoTelefone && campoTelefone.value.trim()) {
                var telefoneValido = /^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/.test(campoTelefone.value.trim());
                if (!telefoneValido) {
                    valido = false;
                    campoTelefone.classList.add('input-erro');
                } else {
                    campoTelefone.classList.remove('input-erro');
                }
            }

            if (!valido) {
                event.preventDefault();
            }
        });
    });
});
