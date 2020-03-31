<template>
    <b-modal id="modal-create-subscriber" size="lg"  title="New Subscriber">
        <b-form-group
            label="Name"
            label-for="name"
            label-cols-sm="12"
            label-cols-md="3"
        >
            <b-form-input id="name" v-model="form.name" trim required></b-form-input>
        </b-form-group>

        <b-form-group
            label="Email"
            label-for="email"
            label-cols-sm="12"
            label-cols-md="3"
        >
            <b-form-input id="email" v-model="form.email" trim required type="email"></b-form-input>
        </b-form-group>

        <template v-for="(field,index) in form.fields">
            <b-form-group
                :label="field.title"
                :label-for="field.title"
                label-cols-sm="12"
                label-cols-md="3"
            >
                <b-form-input :id="field.title"
                              v-model="form.fields[index].value">
                </b-form-input>
            </b-form-group>
        </template>

        <b-alert v-model="errors.length > 0" variant="danger" dismissible>
            <ul class="list-unstyled mb-0">
                <li v-for="error in errors" v-text="error"></li>
            </ul>
        </b-alert>

        <template v-slot:modal-footer>
            <b-button @click="hideModal">
                Cancel
            </b-button>
            <b-button variant="success" @click="createSubscriber">
                Create
            </b-button>
        </template>
    </b-modal>
</template>

<script>
    export default {
        props: ['fields'],
        data() {
            return {
                form: {
                    name: '',
                    email: '',
                    fields: this.fields
                },
                errors: []
            }
        },
        methods: {
            hideModal() {
                this.$bvModal.hide('modal-create-subscriber')
            },
            createSubscriber() {
                const vm = this;
                axios.post('/api/subscribers', this.formData)
                    .then(function (response) {
                        Event.$emit('subscriber:new', response.data);
                        vm.hideModal();
                        vm.resetForm();
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                    });
            },
            resetForm() {
                this.form = {
                    name: '',
                    email: '',
                    fields: this.fields
                };
            }
        },
        computed: {
            formData() {
                return {
                    name: this.form.name,
                    email: this.form.email,
                    fields: _.pick(_.zipObject(
                        _.map(this.fields, 'id'),
                        _.map(this.fields, 'value')
                    ))
                }
            }
        }
    }
</script>
