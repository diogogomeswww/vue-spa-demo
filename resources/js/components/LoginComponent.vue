<template>
    <div class="my-5 col-md-4 mx-auto">
        <b-form @submit="submit">
            <b-form-group
                label="Email"
                label-for="email"
            >
                <b-form-input id="email" v-model="form.email" trim required></b-form-input>
            </b-form-group>

            <b-form-group
                label="Password"
                label-for="password"
            >
                <b-form-input id="password" v-model="form.password" type="password" required></b-form-input>
            </b-form-group>

            <div class="alert alert-danger fade show" role="alert" v-if="error.length">
                <strong v-text="error"></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="error = ''">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <b-button type="submit" variant="primary">Login</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                error: ''
            }
        },
        methods: {
            submit: function() {
                let self = this;
                this.error = '';
                axios.post('/api/token', self.form)
                    .then(function (response) {
                        Event.$emit('user:auth', response.data);
                    })
                    .catch(function () {
                        self.error = 'The provided credentials are incorrect.';
                    });
            }
        }
    };
</script>
