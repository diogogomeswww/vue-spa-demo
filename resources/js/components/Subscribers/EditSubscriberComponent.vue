<template>
    <b-modal id="modal-edit-subscriber" size="lg" title="Edit Subscriber">
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
                :label-for="`field_${field.id}`"
                label-cols-sm="12"
                label-cols-md="3"
            >
                <b-form-checkbox v-if="field.type === 'boolean'"
                    :id="`field_${field.id}`"
                    v-model="form.fields[index].value"
                    value="1"
                    unchecked-value="0"
                    class="mt-1"
                ></b-form-checkbox>

                <b-form-datepicker v-else-if="field.type === 'date'"
                    :id="`field_${field.id}`"
                    v-model="form.fields[index].value">
                </b-form-datepicker>

                <b-form-input v-else
                              :id="`field_${field.id}`"
                              :type="field.type === 'string' ? 'text' : field.type"
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
            <b-button variant="success" @click="editSubscriber">
                Update
            </b-button>
        </template>
    </b-modal>
</template>

<script>
    export default {
        props: ['fields', 'subscriber'],
        data() {
            return {
                errors: []
            }
        },
        methods: {
            hideModal() {
                this.$bvModal.hide('modal-edit-subscriber')
            },
            editSubscriber() {
                const vm = this;
                axios.put(`/api/subscribers/${this.subscriber.id}`, this.formData)
                    .then(function (response) {
                        Event.$emit('subscriber:updated', response.data);
                        vm.hideModal();
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                    });
            }
        },
        computed: {
            form() {
                return {
                    name: this.subscriber.name,
                    email: this.subscriber.email,
                    fields: _.map(_.unionBy(this.subscriber.fields, this.fields, 'id'), function(field) {
                        field.value = field.pivot ? field.pivot.value : '';
                        return field;
                    })
                }
            },
            formData() {
                return {
                    name: this.form.name,
                    email: this.form.email,
                    fields: _.pickBy(_.zipObject(
                        _.map(this.form.fields, 'id'),
                        _.map(this.form.fields, 'value')
                    ))
                }
            }
        }
    }
</script>
