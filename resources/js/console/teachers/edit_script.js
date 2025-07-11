$(document).ready(function () {
    const validateForm = (formSelector, fieldsConfig) => {
        const formElement = document.querySelector(formSelector);
        if (!formElement) return;

        FormValidation.formValidation(formElement, {
            fields: fieldsConfig,
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: "",
                    rowSelector: ".form-floating",
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
            init: (instance) => {
                instance.on("plugins.message.placed", (e) => {
                    if (
                        e.element.parentElement.classList.contains(
                            "input-group"
                        )
                    ) {
                        e.element.parentElement.insertAdjacentElement(
                            "afterend",
                            e.messageElement
                        );
                    }
                });

                instance.on("core.element.validated", (e) => {
                    if (e.valid) {
                        e.element.classList.add("is-valid");
                    }
                });

                instance.on("core.form.valid", () => {
                    formElement.submit();
                });
            },
        });
    };

    validateForm("#createTeacherForm", {
        class_id: {
            validators: {
                notEmpty: {
                    message: "Please select a class",
                },
            },
        },
        name: {
            validators: {
                notEmpty: {
                    message: "Please enter a name",
                },
                stringLength: {
                    min: 2,
                    message: "Name must be at least 2 characters long",
                },
            },
        },
        email: {
            validators: {
                notEmpty: {
                    message: "Please enter an email address",
                },
                emailAddress: {
                    message: "Please enter a valid email address",
                },
            },
        },
        nip: {
            validators: {
                notEmpty: {
                    message: "Please enter a NIP",
                },
                stringLength: {
                    min: 10,
                    max: 18,
                    message: "NIP must be between 10 and 18 characters long",
                },
            },
        },
        password: {
            validators: {
                stringLength: {
                    min: 8,
                    message: "Password must be at least 8 characters long",
                },
            },
        },
        password_confirmation: {
            validators: {
                identical: {
                    compare: () => formElement.querySelector('[name="password"]').value,
                    message: "The password and its confirmation are not the same",
                },
            },
        },
    });
});
