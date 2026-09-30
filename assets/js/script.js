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

            // OBS: o campo "telefone" não recebe nenhuma validação de formato aqui
            // (ponto proposital para a etapa de manutenção/análise futura).

            if (!valido) {
                event.preventDefault();
            }
        });
    });
});
