<template>
    <div class="col-md-2 d-none d-md-block bg-light card my-4 ml-4 card">
        <div class="card-body">
            <ul class="nav flex-column">
                <li class="nav-item text-center mb-3">
                    <h1 v-text="user.name"></h1>

                    <img :src="avatar"
                         :alt="user.name"
                         class="img-thumbnail rounded-circle">
                </li>
            </ul>

            <div class="list-group text-right my-4 list-group-flush">
                <router-link :class="isActive('fields')" class="list-group-item list-group-item-action" to="/fields">
                    Fields
                </router-link>
                <router-link :class="isActive('subscribers')" class="list-group-item list-group-item-action" to="/subscribers">
                    Subscribers
                </router-link>
                <a href="#" class="list-group-item list-group-item-action" @click="logout">
                    Logout
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        methods: {
            logout() {
                Event.$emit('user:logout');
            },
            isActive(path) {
                return this.$route.path.includes(path)
                    ? 'active'
                    : '';
            }
        },
        computed: {
            avatar() {
                return `https://i.pravatar.cc/150?u=${this.user.email}`;
            }
        }
    }
</script>

