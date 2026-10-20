document.addEventListener('DOMContentLoaded', function () {
    var authMode = document.body.dataset.authMode || 'login';
    var tabs = document.querySelectorAll('[data-auth-toggle]');
    var panels = document.querySelectorAll('[data-auth-panel]');

    function showPanel(mode) {
        tabs.forEach(function (tab) {
            tab.classList.toggle('active', tab.dataset.authToggle === (mode === 'register' ? 'register' : 'login'));
        });

        panels.forEach(function (panel) {
            panel.classList.toggle('active', panel.dataset.authPanel === (mode === 'register' ? 'register' : 'login'));
        });
    }

    if (tabs.length && panels.length) {
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var selected = tab.dataset.authToggle === 'register' ? 'register' : 'login';
                showPanel(selected);
            });
        });

        showPanel(authMode === 'register' ? 'register' : 'login');
    }

    document.querySelectorAll('.btn-show-password').forEach(function (button) {
        button.addEventListener('click', function () {
            var campo = document.getElementById(button.dataset.passwordField);
            if (!campo) {
                return;
            }

            var mostrar = campo.type === 'password';
            campo.type = mostrar ? 'text' : 'password';
            button.textContent = mostrar ? 'Ocultar' : 'Mostrar';
        });
    });

    var forms = document.querySelectorAll('form');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (form.classList.contains('form-excluir')) {
                if (!confirm('Tem certeza que deseja excluir este cliente? Essa ação não pode ser desfeita.')) {
                    event.preventDefault();
                }
                return;
            }

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

            if (form.querySelector('input[name="confirmar_senha"]')) {
                var senha = form.querySelector('input[name="senha"]');
                var confirmarSenha = form.querySelector('input[name="confirmar_senha"]');

                if (senha && confirmarSenha && senha.value !== confirmarSenha.value) {
                    valido = false;
                    confirmarSenha.classList.add('input-erro');
                }
            }

            if (!valido) {
                event.preventDefault();
            }
        });
    });
});
