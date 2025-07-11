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

    validateForm("#createClassRoomForm", {
        school_id: {
            validators: {
                notEmpty: {
                    message: "The school is required",
                },
            },
        },
        name: {
            validators: {
                notEmpty: {
                    message: "The classroom name is required",
                },
                stringLength: {
                    max: 255,
                    message: "The classroom name must be less than 255 characters",
                },
            },
        },
        grade: {
            validators: {
                notEmpty: {
                    message: "The grade is required",
                },
                stringLength: {
                    max: 255,
                    message: "The grade must be less than 255 characters",
                },
            },
        },
    });
});
