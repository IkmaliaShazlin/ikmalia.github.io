// Initialize JustValidate on the form with ID "signup"
const validation = new JustValidate("#signup");

validation
    // Validate username: required
    .addField("#username", [
        {
            rule: "required" // Must not be empty
        }
    ])

    // Validate email: required, valid email format, and uniqueness check via AJAX
    .addField("#email", [
        {
            rule: "required" // Must not be empty
        },
        {
            rule: "email" // Must be a valid email
        },
        {
            // Custom validator: check email availability via server
            validator: (value) => () => {
                return fetch("validate-email.php?email=" + encodeURIComponent(value))
                    .then(function(response) {
                        return response.json();
                        })
                            .then(function(json) {
                        return json.available; // Return true if available
                    });
            },
            errorMessage: "email already taken" // Shown if validation fails
        }
    ])
    // Validate password: required and password rule (like min length, etc.)
    .addField("#password", [
        {
            rule: "required"
        },
        {
            rule: "password" // Password strength rule (depends on plugin config)
        }
    ])
    // Confirm password: must match the password field
    .addField("#password_confirmation",[
        {
            validator: (value, fields) => {
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Passwords should match" // Error if mismatch
        }
    ])
    // On successful validation, submit the form
    .onSuccess((event) => {
        document.getElementById("signup").submit();
    });
