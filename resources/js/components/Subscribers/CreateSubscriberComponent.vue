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

        <template v-for="field in fields">
            <b-form-group
                :label="field.title"
                :label-for="`field_${field.id}`"
                label-cols-sm="12"
                label-cols-md="3"
            >
                <b-form-checkbox v-if="field.type === 'boolean'"
                                 :id="`field_${field.id}`"
                                 v-model="form.fields[field.id]"
                                 value="1"
                                 unchecked-value="0"
                                 class="mt-1"
                ></b-form-checkbox>

                <b-form-datepicker v-else-if="field.type === 'date'"
                                   :id="`field_${field.id}`"
                                   v-model="form.fields[field.id]">
                </b-form-datepicker>

                <b-form-input v-else
                              :id="`field_${field.id}`"
                              :type="field.type === 'string' ? 'text' : field.type"
                              v-model="form.fields[field.id]">
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
                    fields: {}
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
                axios.post('/api/subscribers', _.pickBy(this.form))
                    .then(function (response) {
                        vm.resetForm();
                        vm.hideModal();
                        Event.$emit('subscriber:new', response.data);
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                    });
            },
            resetForm() {
                this.form = {
                    name: '',
                    email: '',
                    fields: {}
                };
                this.errors = [];
            }
        }
    }
</script>
