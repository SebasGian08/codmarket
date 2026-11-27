(function () {
    var wizard = document.querySelector('[data-contact-wizard]');
    if (!wizard) return;

    var resetButton = wizard.querySelector('[data-wizard-reset]');
    if (resetButton) {
        resetButton.addEventListener('click', function () {
            window.location.reload();
        });
        return;
    }

    var form = wizard.querySelector('[data-contact-wizard-form]');
    if (!form) return;

    var panels = Array.prototype.slice.call(wizard.querySelectorAll('[data-wizard-panel]'));
    var stepLabels = Array.prototype.slice.call(wizard.querySelectorAll('[data-wizard-step-label]'));
    var progress = wizard.querySelector('[data-wizard-progress]');
    var progressFill = wizard.querySelector('[data-wizard-progress-fill]');
    var currentStepInput = form.querySelector('[data-wizard-current-step]');
    var serviceInput = form.querySelector('[data-service-input]');
    var serviceSummary = wizard.querySelector('[data-service-summary]');
    var currentStep = 1;

    function getField(name) {
        return form.querySelector('[data-wizard-field="' + name + '"]');
    }

    function clearError(name) {
        var field = getField(name);
        if (!field) return;

        field.classList.remove('has-error');
        var error = field.querySelector('[data-wizard-error]');
        if (error) error.textContent = '';

        field.querySelectorAll('[aria-invalid="true"]').forEach(function (element) {
            element.removeAttribute('aria-invalid');
        });
    }

    function setError(name, message) {
        var field = getField(name);
        if (!field) return;

        field.classList.add('has-error');
        var error = field.querySelector('[data-wizard-error]');
        if (error) error.textContent = message;

        var control = field.querySelector('input:not([type="hidden"]), textarea');
        if (control) control.setAttribute('aria-invalid', 'true');
    }

    function updateServiceSummary() {
        var selectedCard = wizard.querySelector('.contact-wizard__service-card.is-selected');
        if (selectedCard && serviceSummary) {
            serviceSummary.textContent = selectedCard.dataset.serviceName;
        }
    }

    function setStep(step, focusHeading) {
        currentStep = Math.max(1, Math.min(3, step));
        currentStepInput.value = currentStep;

        panels.forEach(function (panel) {
            panel.hidden = Number(panel.dataset.wizardPanel) !== currentStep;
        });

        stepLabels.forEach(function (item) {
            var itemStep = Number(item.dataset.wizardStepLabel);
            item.classList.toggle('is-current', itemStep === currentStep);
            item.classList.toggle('is-complete', itemStep < currentStep);

            if (itemStep === currentStep) {
                item.setAttribute('aria-current', 'step');
            } else {
                item.removeAttribute('aria-current');
            }
        });

        if (progress) {
            progress.setAttribute('aria-valuenow', currentStep);
        }

        if (progressFill) {
            progressFill.style.width = ((currentStep - 1) / 2) * 100 + '%';
        }

        if (focusHeading) {
            var heading = wizard.querySelector('[data-wizard-panel="' + currentStep + '"] [data-wizard-heading]');
            if (heading) heading.focus();
        }
    }

    function validateService() {
        if (serviceInput.value) {
            clearError('servicio');
            return true;
        }

        setError('servicio', 'Selecciona un servicio para continuar.');
        return false;
    }

    function validateStepTwo() {
        var valid = true;
        var fields = [
            { name: 'nombre', message: 'Escribe tus nombres.' },
            { name: 'apellidos', message: 'Escribe tus apellidos.' },
            { name: 'email', message: 'Ingresa un correo electrónico válido.' },
            { name: 'telefono', message: 'Ingresa un celular de 9 dígitos que empiece con 9.' }
        ];

        fields.forEach(function (item) {
            var field = form.elements[item.name];
            var value = field ? field.value.trim() : '';
            var isValid = value.length > 0;

            if (item.name === 'email') {
                isValid = isValid && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            } else if (item.name === 'telefono') {
                isValid = /^9[0-9]{8}$/.test(value);
            }

            if (!isValid) {
                setError(item.name, item.message);
                valid = false;
            } else {
                clearError(item.name);
            }
        });

        return valid;
    }

    function validateStepThree() {
        var valid = true;
        var message = form.elements.message.value.trim();
        var consent = form.elements.acepta_privacidad;

        if (!message) {
            setError('message', 'Escribe un mensaje para continuar.');
            valid = false;
        } else if (message.length > 1000) {
            setError('message', 'El mensaje no puede superar los 1000 caracteres.');
            valid = false;
        } else {
            clearError('message');
        }

        if (!consent.checked) {
            setError('acepta_privacidad', 'Debes aceptar el tratamiento de tus datos para enviar la consulta.');
            valid = false;
        } else {
            clearError('acepta_privacidad');
        }

        if (wizard.dataset.turnstileRequired === 'true') {
            var turnstileResponse = form.querySelector('[name="cf-turnstile-response"]');
            if (!turnstileResponse || !turnstileResponse.value) {
                setError('turnstile', 'Completa la verificación anti-robot.');
                valid = false;
            } else {
                clearError('turnstile');
            }
        }

        return valid;
    }

    wizard.querySelectorAll('[data-service-id]').forEach(function (card) {
        card.addEventListener('click', function () {
            wizard.querySelectorAll('[data-service-id]').forEach(function (item) {
                item.classList.remove('is-selected');
                item.setAttribute('aria-pressed', 'false');
            });

            card.classList.add('is-selected');
            card.setAttribute('aria-pressed', 'true');
            serviceInput.value = card.dataset.serviceId;
            updateServiceSummary();
            clearError('servicio');
        });
    });

    wizard.querySelectorAll('[data-wizard-next]').forEach(function (button) {
        button.addEventListener('click', function () {
            var valid = currentStep === 1 ? validateService() : validateStepTwo();
            if (valid) setStep(currentStep + 1, true);
        });
    });

    wizard.querySelectorAll('[data-wizard-back]').forEach(function (button) {
        button.addEventListener('click', function () {
            setStep(currentStep - 1, true);
        });
    });

    form.querySelectorAll('[data-validate-on-input]').forEach(function (field) {
        field.addEventListener('input', function () {
            if (field.name === 'telefono') {
                field.value = field.value.replace(/\D/g, '').slice(0, 9);
            }
            clearError(field.name);
        });
    });

    var consentCheckbox = form.elements.acepta_privacidad;
    consentCheckbox.addEventListener('change', function () {
        if (consentCheckbox.checked) clearError('acepta_privacidad');
    });

    form.addEventListener('submit', function (event) {
        if (currentStep < 3) {
            event.preventDefault();
            var valid = currentStep === 1 ? validateService() : validateStepTwo();
            if (valid) setStep(currentStep + 1, true);
            return;
        }

        currentStepInput.value = 3;

        if (!validateStepThree()) {
            event.preventDefault();
        }
    });

    var selectedService = null;
    wizard.querySelectorAll('[data-service-id]').forEach(function (card) {
        if (card.dataset.serviceId === serviceInput.value) {
            selectedService = card;
        }
    });
    if (selectedService) {
        selectedService.classList.add('is-selected');
        selectedService.setAttribute('aria-pressed', 'true');
        updateServiceSummary();
    } else {
        serviceInput.value = '';
    }

    var requestedStep = Number(wizard.dataset.initialStep) || 1;
    setStep(requestedStep, false);
})();
