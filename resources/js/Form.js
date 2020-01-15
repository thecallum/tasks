// Reusable Form Library
const axios = require("axios");

class Form {
    fields = null; /* List of form fields */
    token;
    errors = {};
    loading = false;

    constructor(fields) {
        this.token = this.loadCSRFToken();
        this.loadFormFields(fields);
    }

    loadFormFields(fields) {
        /* fields must be empty or an object(array or object) */

        if (fields === undefined) return;

        if (typeof fields !== "object") {
            throw new Error(
                "Invalid form fields, accepts: ( [field, field] or {field: '', field: ''} )"
            );
        }

        if (fields instanceof Array) {
            // array
            this.fields = fields;

            fields.forEach(field => {
                this[field] = "";
            });
        } else {
            // object
            this.fields = Object.keys(fields);

            Object.keys(fields).forEach(field => {
                this[field] = fields[field];
            });
        }
    }

    loadFields() {
        if (this.fields === null) return {};

        const response = {};

        this.fields.forEach(field => {
            response[field] = this[field];
        });

        return response;
    }

    reset() {
        this.fields.forEach(field => {
            this[field] = "";
        });
        this.errors = {};
    }

    loadCSRFToken() {
        try {
            const token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            return token;
        } catch (e) {
            console.log("ERROR", "Cannot find CSRF Token!");
        }
    }

    submit(method, url) {
        return new Promise((resolve, reject) => {
            const requestData = this.loadFields();

            // nullify empty fields
            if (this.fields !== null) {
                for (let field in requestData) {
                    if (requestData[field] === "") requestData[field] = null;
                }
            }

            // Add laravel fields
            requestData["_token"] = this.token;
            requestData["_method"] = method.toUpperCase();

            this.loading = true;
            this.errors = {};

            axios
                .post(url, requestData)
                .then(response => {
                    resolve(response);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    reject(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        });
    }

    /* Helper Functions */
    get(url) {
        return this.submit("GET", url);
    }

    post(url) {
        return this.submit("POST", url);
    }

    put(url) {
        return this.submit("PUT", url);
    }

    patch(url) {
        return this.submit("PATCH", url);
    }

    delete(url) {
        return this.submit("DELETE", url);
    }
}

module.exports = Form;
