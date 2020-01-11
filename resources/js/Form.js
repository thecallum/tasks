// Reusable Form Library

class Form {
    fields;
    token;
    errors = {};
    loading = false;

    constructor(fields) {
        this.token = this.loadCSRFToken();
        this.loadFormFields(fields);
    }

    loadFormFields(fields) {
        this.fields = fields;

        fields.forEach(field => {
            this[field] = "";
        });
    }

    loadFields() {
        const response = {};

        this.fields.forEach(field => {
            response[field] = this[field];
        });

        return response;
    }

    submit(method, url) {
        return new Promise((resolve, reject) => {
            const requestData = this.loadFields();

            // Add laravel fields
            requestData["_token"] = this.token;
            requestData["_method"] = method.toUpperCase();

            this.loading = true;
            this.errors = {};

            axios.post(url, requestData)
                .then(response => {
                    resolve(response);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    reject(error)
                })
                .finally(() => {
                    this.loading = false;
                });
        });
            
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
}

module.exports = Form;