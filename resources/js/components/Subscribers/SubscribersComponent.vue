<template>
    <div>
        <div>
            <h2>Subscribers
                <a href="#/subscribers" class="ml-2" v-b-modal.modal-create-subscriber >
                    <b-icon-plus></b-icon-plus>
                </a>
            </h2>
        </div>
        <div>
            <b-table hover
                     :items="subscribers"
                     :fields="columns">

                <template v-slot:cell(fields)="data">
                    <dl v-if="data.value.length">
                        <template v-for="field in data.value">
                            <dt><strong>{{ field.title }}</strong></dt>
                            <dd>{{ fieldValue(field) }}</dd>
                        </template>
                    </dl>
                </template>

                <template v-slot:cell(actions)="data">
                    <a href="#" @click.prevent="deleteSubscriber(data.item.id)" class="mr-2">
                        <b-icon-trash scale="1.5"></b-icon-trash>
                    </a>
                    <a href="#" v-b-modal.modal-edit-subscriber @click.prevent="setCurrent(data.item)">
                        <b-icon-pencil scale="1.5"></b-icon-pencil>
                    </a>
                </template>

            </b-table>
        </div>

        <create-subscriber :fields="fields"></create-subscriber>
        <edit-subscriber :fields="fields" :subscriber="current" v-if="current"></edit-subscriber>
    </div>
</template>

<script>
    import CreateSubscriber from "./CreateSubscriberComponent";
    import EditSubscriber from "./EditSubscriberComponent";

    export default {
        props: ['subscribers', 'fields'],
        components: {
            CreateSubscriber, EditSubscriber
        },
        data() {
            return {
                current: null,
                columns: [
                    {
                        key: 'id',
                        sortable: true
                    },
                    {
                        key: 'name',
                        sortable: true
                    },
                    {
                        key: 'email',
                        sortable: true
                    },
                    {
                        key: 'state',
                        sortable: true,
                        formatter: value => {
                            return value[0].toUpperCase() + value.substring(1).toLowerCase();
                        }
                    },
                    {
                        key: 'fields',
                        sortable: false,
                        label: 'Custom Fields'
                    },
                    {
                        key: 'actions',
                        label: ''
                    }
                ]
            }
        },
        methods: {
            fieldValue(field) {
                if (field.type === 'boolean') {
                    return field.pivot.value === 1 ? 'Yes' : 'No';
                }
                return field.pivot.value;
            },
            setCurrent(subscriber) {
                this.current = subscriber;
            },
            deleteSubscriber(subscriber_id) {
                axios.delete(`api/subscribers/${subscriber_id}`)
                    .then(function() {
                        Event.$emit('subscriber:deleted', subscriber_id);
                    })
                    .catch(console.error);
            }
        }
    }
</script>

<style>
    dl {
        display: grid;
        grid-template-columns: auto auto;
    }

    dd {
        margin: 0
    }
</style>
