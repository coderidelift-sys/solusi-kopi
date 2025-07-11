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

    validateForm("#createSchoolForm", {
        name: {
            validators: {
                notEmpty: {
                    message: "The school name is required",
                },
            },
        },
        address: {
            validators: {
                notEmpty: {
                    message: "The address is required",
                },
            },
        },
        district: {
            validators: {
                notEmpty: {
                    message: "The district is required",
                },
            },
        },
        city: {
            validators: {
                notEmpty: {
                    message: "The city is required",
                },
            },
        },
        province: {
            validators: {
                notEmpty: {
                    message: "The province is required",
                },
            },
        },
        principal_name: {
            validators: {
                notEmpty: {
                    message: "The principal name is required",
                },
            },
        },
        principal_code: {
            validators: {
                notEmpty: {
                    message: "The principal code is required",
                },
            },
        },
        logo: {
            validators: {
                file: {
                    extension: "jpeg,jpg,png",
                    type: "image/jpeg,image/png",
                    maxSize: 2 * 1024 * 1024, // 2 MB
                    message: "The selected file is not valid",
                },
                notEmpty: {
                    message: "The logo is required",
                },
            },
        },
    });
});
