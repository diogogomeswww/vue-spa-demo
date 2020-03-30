<template>
    <b-modal id="modal-create-field" title="New Field">
        <b-form-group
            label="Title"
            label-for="title"
        >
            <b-form-input id="title" v-model="form.title" trim required></b-form-input>
        </b-form-group>
        <b-form-group
            label="Type"
            label-for="type"
        >
            <b-form-select v-model="form.type"
                           :options="types"
                           required
            ></b-form-select>
        </b-form-group>

        <template v-slot:modal-footer>
            <b-button @click="hideModal">
                Cancel
            </b-button>
            <b-button variant="success" @click="createField">
                Create
            </b-button>
        </template>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                types: [
                    {value: null, text: 'Please select a type'},
                    {value: 'string', text: 'String'},
                    {value: 'date', text: 'Date'},
                    {value: 'boolean', text: 'Boolean'},
                    {value: 'number', text: 'Number'},
                ],
                form: {
                    title: '',
                    type: 'string'
                },
            }
        },
        methods: {
            hideModal() {
                this.$bvModal.hide('modal-create-field')
            },
            createField() {
                const vm = this;
                axios.post('/api/fields', this.form)
                    .then(function (response) {
                        Event.$emit('field:new', response.data);
                        vm.hideModal();
                    })
                    .catch(console.error);
            }
        }
    }
</script>
