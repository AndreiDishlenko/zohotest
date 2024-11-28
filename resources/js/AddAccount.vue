<template>
    <Form ref="formRef" class="" disabled>
        <h4 class="form-header mb-4">NEW ACCOUNT</h4>
        <div class="form-group mb-3">
            <label class="form-label">Account name:</label>
            <Field type="text" class="form-control" placeholder="Enter account name" autofocus
                name="account_name" 
                v-model="account_name"
                :rules="validateName"
                :validateOnBlur="false"
                :disabled="isDisabled"/>
            <ErrorMessage name="account_name" class="form-error"/>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Website:</label>
            <Field type="text" class="form-control" placeholder="Enter website" 
                name="website" 
                v-model="website" 
                :rules="validateWebsite"
                :validateOnBlur="false"
                :disabled="isDisabled"/>
            <ErrorMessage name="website" class="form-error"/>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Phone:</label>
            <Field type="text" class="form-control" placeholder="Enter phone (0xx) xxx-xx-xx" 
                name="phone" 
                v-model="phone"
                :rules="validatePhone"
                :validateOnBlur="false"
                mask="'(0##) ###-##-##'"
                masked="false"
                v-mask="'(0##) ###-##-##'"
                :disabled="isDisabled"/>
            <ErrorMessage name="phone" class="form-error"/>
        </div>  
        
        <p v-if="form_error" class="form-error mb-4">{{ form_error }}</p>

        <div class="d-flex">
            <button id="submitbutton" class="submit w-100 me-1" @click.prevent="submitForm" :disabled="isDisabled">Add account</button>
            <button id="cancelbutton" class="bg-secondary w-100 ms-1" @click.prevent="$emit('closeForm')" :disabled="isDisabled">Cancel</button>
        </div>
        
    </Form>

</template>

<script>
    import { Form, Field, ErrorMessage  } from 'vee-validate';
    import ApiClient from "./apiclient.js"
    import {mask} from 'vue-the-mask'
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    export default {
        components: {Form, Field, ErrorMessage},
        data() {
            return {
                account_name: 'Test 2',
                website: 'www.test.com',
                phone: '(067) 555-55-55',
                form_error: '',
                isDisabled: false
            }
        },
        directives: {mask},
        methods: {
            shown() {
                this.resetForm(); 
                this.form_error = '';
            },
            validateName(value) {
                if ( !value ) 
                    return 'This field is required.';

                const regex = /^([a-zA-Z0-9\(\)]+\s*)+$/;
                if (!regex.test(value)) 
                    return 'This field must be a valid name';

                return true;
            },
            validateWebsite(value) {
                if ( !value ) 
                    return 'This field is required.';

                const regex = /^(www\.)?[\w.-]+\.[a-z]{2,6}(\/.*)?$/;
                if (!regex.test(value)) 
                    return 'This field must be a valid website name';

                return true;
            },
            validatePhone(value) {
                if ( !value ) 
                    return 'This field is required.';

                const regex = /^\(\d{3}\) \d{3}-\d{2}-\d{2}$/;
                if (!regex.test(value)) {
                    return 'This field must be a valid phone number';
                }

                return true;
            },
            resetForm() {
                this.$refs.formRef.resetForm({
                    values: {
                        account_name: '',
                        website: '',
                        phone: '',
                    }
                });
                this.isDisabled = false;
            },

            async submitForm() {
                const { validate } = this.$refs.formRef; 
                const { valid, errors } = await validate(); 
                if ( !valid ) {
                    console.log('Form contains errors:', errors);
                    return false;
                }

                try {                   
                    this.isDisabled = true;
                    let apiClient = await ApiClient.init(true);

                    let json_data = {
                        "Account_Name": this.account_name,
                        "Website": this.website,
                        "Phone": this.phone
                    }

                    const response = await apiClient.post( '/accounts', json_data );
                    if (response.status!=200)
                        throw new TypeError(response.data?.error);

                    this.form_error = '';
                    this.isDisabled = false;                  
                    toast('Account added.', {position: toast.POSITION.BOTTOM_CENTER})
                    this.$emit('closeForm', { name: this.account_name });
                } catch (error) {
                    this.form_error = "Account adding error: "+error.message;
                    this.isDisabled = false;                    
                }
            },
        }
    }
</script>

<style>
</style>