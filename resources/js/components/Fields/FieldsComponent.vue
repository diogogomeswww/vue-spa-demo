<template>
    <div>
        <div>
            <h2>Fields
                <a href="#" @click.prevent="$bvModal.show('modal-create-field')" class="ml-2">
                    <b-icon-plus></b-icon-plus>
                </a>
            </h2>
        </div>
        <div>
            <b-table hover
                     :items="fields"
                     :fields="columns">

                <template v-slot:cell(actions)="data">
                    <a href="#" @click.prevent="deleteField(data.item.id)" class="mr-2">
                        <b-icon-trash scale="1.5"></b-icon-trash>
                    </a>
                    <a href="#" v-b-modal.modal-edit-field @click.prevent="current = data.item">
                        <b-icon-pencil scale="1.5"></b-icon-pencil>
                    </a>
                </template>

            </b-table>
        </div>

        <create-field></create-field>
        <edit-field :field="current"></edit-field>
    </div>
</template>

<script>
    import Sidebar from "../SidebarComponent";
    import CreateField from "./CreateFieldComponent";
    import EditField from "./EditFieldComponent";

    export default {
        components: {
            Sidebar, CreateField, EditField
        },
        props: ['fields'],
        data() {
            return {
                current: {
                    title: '',
                    type: 'string'
                },
                columns: [
                    {
                        key: 'id',
                        sortable: true
                    },
                    {
                        key: 'title',
                        sortable: true
                    },
                    {
                        key: 'type',
                        sortable: true,
                        formatter: value => {
                            return value[0].toUpperCase() + value.substring(1).toLowerCase();
                        }
                    },
                    {
                        key: 'actions',
                        label: ''
                    }
                ]
            }
        },
        methods: {
            deleteField(field_id) {
                axios.delete(`api/fields/${field_id}`)
                    .then(function() {
                        Event.$emit('field:deleted', field_id);
                    })
                    .catch(console.error);
            }
        }
    }
</script>
