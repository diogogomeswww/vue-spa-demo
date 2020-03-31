<template>
    <b-modal id="modal-edit-field" title="Edit Field">
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
            <!-- do not allow editing the type -->
            <b-form-input :value="form.type" disabled></b-form-input>
        </b-form-group>

        <template v-slot:modal-footer>
            <b-button @click="hideModal">
                Cancel
            </b-button>
            <b-button variant="success" @click="updateField">
                Update
            </b-button>
        </template>
    </b-modal>
</template>

<script>
    export default {
        props: ['field'],
        methods: {
            hideModal() {
                this.$bvModal.hide('modal-edit-field')
            },
            updateField() {
                const vm = this;
                axios.put(`/api/fields/${this.field.id}`, this.formData)
                    .then(function (response) {
                        Event.$emit('field:updated', response.data);
                        vm.hideModal();
                    })
                    .catch(console.error);
            }
        },
        computed: {
            form() {
                return {
                    title: this.field.title,
                    type: this.field.type[0].toUpperCase() + this.field.type.substring(1)
                }
            },
            formData() {
                return {
                    // do not allow editing the type
                    title: this.form.title
                }
            }
        }
    }
</script>
