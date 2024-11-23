<template>

    <div class="wrapper ">
        <Form ref="formRef" class="form bg-white">
            <h4 class="form-header mb-4">NEW DEAL</h4>
            <div class="form-group mb-3">
                <label class="form-label">Deal name:</label>
                <Field id="dealname" type="text" class="form-control"  placeholder="Enter deal name"
                    name="deal_name"
                    v-model="deal_name"
                    :rules="validateName"
                    :validateOnBlur="false"
                    :disabled="isDisabled"/>
                <ErrorMessage name="deal_name" class="form-error"/>
            </div>
            <div class="form-group d-flex mb-3">
                <label class="form-label">Account name:</label>
                <div class="d-flex">
                    <div class="me-1">
                        <Field type="search" ref="AccountName" class="form-control me-1" placeholder="Enter account name" 
                            name="account_name"
                            v-model="account_name"
                            @input="searchAccountName($event.target.value)" 
                            @blur="leaveAccountField" 
                            :rules="validateAccountName"
                            :validateOnBlur="false"
                            :validateOnChange="false"
                            :validateOnInput="false"
                            autocomplete="off"
                            :disabled="isDisabled"/>
                        <div v-if="show_dropdown" ref="dropdown" class="dropdown" tabindex="-1"> 
                            <a v-for="item in filtred_accounts" class="dropdown-item py-1" href="#" @click="selectAccountName(item)"  tabindex="-1">{{ item }}</a> 
                        </div>
                    </div>
                    <button id="addbutton" tabindex="-1" @click.prevent="addAccount()" :disabled="isDisabled">+</button>
                </div>                    
                <ErrorMessage name="account_name" class="form-error"/>                
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Date of conclusion:</label>
                <Field type="text" class="form-control"  placeholder="Enter deal start date in dd.mm.yyyy format" 
                    name="closing_date"
                    v-model="closing_date" 
                    :rules="validateDate"
                    :validateOnBlur="false"
                    :disabled="isDisabled"/>
                <ErrorMessage name="closing_date" class="form-error"/>
            </div>
            <div class="form-group mb-4">
                <label class="form-label">Deal stage:</label>
                <Field type="text" class="form-control"  placeholder="Enter deal stage" 
                    name="stage" 
                    v-model="stage"
                    :rules="validateStage"
                    :disabled="isDisabled"
                    :validateOnBlur="false"/>
                <ErrorMessage name="stage" class="form-error"/>
            </div>  
            
            <p v-if="form_error" class="form-error mb-4" style="white-space: pre-line;">{{ form_error }}</p>

            <button id="submitbutton" class="submit w-100" @click="submitForm" :disabled="isDisabled">Add deal</button>
        </Form>
    </div>

    <ModalWrapper ref="modal_card" instance="modalWindow" :size="'modal-md'" :esc_close="true" @show="$refs.modal_form.shown?.();">
        <AddAccountForm ref="modal_form" @closeForm="closeAddAccountAction"/>
    </ModalWrapper>

</template>

<script>
    import ModalWrapper from './ModalWrapper.vue';
    import AddAccountForm from './AddAccount.vue';

    import { Form, Field, ErrorMessage, useForm  } from 'vee-validate';
    import axios from "axios"
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    export default {
        components: {Form, Field, ErrorMessage, useForm, ModalWrapper, AddAccountForm},
        data() {
            return {
                deal_name: '',
                account_name: '',
                closing_date: '',
                stage: '',

                accounts: [],
                filtred_accounts: [],

                show_dropdown: false,

                isDisabled: false,
                form_error: '',
            }
        },
        mounted() {
            this.refreshAccountsNames();
            this.focusDealName();
        },
        methods: {
            async refreshAccountsNames() {
                // console.log('refreshAccountsNames');
                let pluck = function(arr, key) {
                    var newArr = [];
                    for (let i = 0; i < arr.length; i++) {
                        newArr.push( arr[i][key] );
                    };

                    newArr = newArr.filter((element, index) => {
                        return newArr.indexOf(element) === index;
                    });

                    return newArr;
                }
                
                try {                   
                    const response = await axios.get( window.location.origin.replace(/[#\/]$/, '')+"/api/accounts" );
                    this.accounts = pluck( response.data, "Account_Name")
                    // console.log('Accounts result', this.accounts)
                } catch (error) {
                    console.error("Ошибка при выполнении запроса:", error);
                    this.form_error = "Ошибка загрузки формы";
                }
            },

            focusDealName() {
                const input = document.getElementById('dealname'); 
                if (input) { input.focus(); }
            },

            // Account field functions
            searchAccountName(newValue) {
                // console.log('searchAccountName', newValue);               
                if ( !newValue ) {
                    this.show_dropdown = false;
                    this.filtred_accounts = this.accounts;
                }
                
                if (!this.accounts.length) 
                    return false;
                
                if ( newValue ) {
                    this.filtred_accounts = this.accounts.filter((t)=>t.toLowerCase().includes(newValue.toLowerCase()))
                    if (!this.filtred_accounts.length) 
                        this.show_dropdown = false;
                    else
                        this.show_dropdown = true;
                        // console.log(this.$refs.AccountName)
                }
            },
            selectAccountName(selectedValue) {
                // console.log("selectAccountName")
                this.account_name = selectedValue;
                this.show_dropdown = false;
            },
            leaveAccountField(event) {
                if ( event.relatedTarget?.classList?.contains && event.relatedTarget.classList.contains("dropdown-item") ) {
                    return false;
                }                  
                
                this.$refs.AccountName.validate();
                this.show_dropdown=false
            },

            // Adding account from form
            addAccount() {
                this.$refs.modal_card.show();
            },
            closeAddAccountAction(params) {
                this.$refs.modal_card.close();
                if (params?.name) 
                    this.accounts.push(params.name);
            },

            // Validation
            validateName(value) {
                if ( !value ) 
                    return 'This field is required.';

                const regex = /^([a-zA-Z0-9\(\)]+\s*)+$/;
                if (!regex.test(value)) 
                    return 'This field must be a valid deal name';

                return true;
            },
            validateAccountName(value) {                
                if ( !value ) 
                    return 'This field is required.';

                if ( !this.accounts.includes(value) )
                    return 'Account not found. Please add it before continue';

                return true;
            },
            validateDate(value) {
                if ( !value ) 
                    return 'This field is required.';

                const regex = /^\d{2}.\d{2}.\d{4}$/;
                if (!regex.test(value)) 
                    return 'This field must be a valid date';

                return true;
            },
            validateStage(value) {
                if ( !value ) 
                    return 'This field is required.';

                    const regex = /^([a-zA-Z0-9\(\)]+\s*)+$/;
                if (!regex.test(value)) 
                    return 'This field must be a valid string';

                return true; 
            },

            // Form functions
            resetForm() {                 
                this.$refs.formRef.resetForm({
                    values: {
                        deal_name: '',
                        account_name: '',
                        closing_date: '',
                        stage: ''
                    }
                });
                
                this.isDisabled = false;
                this.focusDealName();
            },
            async submitForm() {
                // console.log("Submit form")
                const { validate } = this.$refs.formRef; 
                const { valid, errors } = await validate(); 
                if ( !valid ) 
                    return false;
                

                let url = window.location.origin.replace(/[#\/]$/, '')+"/api/deals";
                let json_data = {
                    "Deal_Name": this.deal_name,
                    "Account_Name": this.account_name,
                    "Closing_Date": this.closing_date,
                    "Stage": this.stage,
                }

                try {                   
                    this.isDisabled = true;
                    const response = await axios.post( url, json_data );

                    if (response?.data?.statuscode==400)
                        this.form_error = "Error "+response?.data?.message+" "+JSON.stringify(response?.data?.details);                        
                    else
                        this.form_error = '';

                    toast('Deal added.', {position: toast.POSITION.BOTTOM_CENTER})
                    this.resetForm();                   
                } catch (error) {
                    this.isDisabled = false;
                    this.form_error = error;
                }
            }
        }
    }
</script>

<style>
    .wrapper {
        width: 100dvw;
        height: 100dvh;
        display: flex;
        justify-content: center;
        align-items: center;

        font-size:14px;
    }

    .form {
        border: 1px solid #cccccc;
        padding: 3rem;
        border-radius: 2rem
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-label {
        margin-bottom: 0.25rem!important;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .form-control {
        min-width: 300px!important;
    }
    .form-control::placeholder {
        color: #cccccc;
        
    }
    .form-error {
        color: red;
    }

    button {
        border: 0px;                      
        color: white;            
    }
    #addbutton {
        /* height: 2.5rem; */
        width: 40px;
        background-color: grey;
        border-radius: 5px;
    }
    #submitbutton, #cancelbutton {
        background-color: green;
        border-radius: 15px;  
        padding: 0.75rem;
    }

    .dropdown {
        position: absolute!important;
        border: 1px solid #eeeeee;
        border-radius: 5px; 
        /* top: 0.25rem; */
        padding: 0.5rem;
        /* width: 305px; */
        background-color: white;
    }

</style>