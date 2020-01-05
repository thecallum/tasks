class Form {
    // Used to find fields
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

    submit(method, url) {
        return new Promise((resolve, reject) => {

            console.log("FORM SUBMIT");

            const requestData = {};

            this.fields.forEach(field => {
                requestData[field] = this[field];
            });

            // Add laravel fields
            requestData["_token"] = this.token;
            requestData["_method"] = method.toUpperCase();

            console.log("Request data", requestData);

            const that = this;

            this.loading = true;
            this.errors = {};

            axios[method.toLowerCase()](url, requestData)
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