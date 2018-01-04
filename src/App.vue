<template>
    <div id="app">
        <h1>{{ title }}</h1>
        <table>
            <user v-for="user in users" v-bind:user="user" v-bind:key="user.name"></user>
        </table>
        <h3>
            <total v-bind:total="total"></total>
        </h3>
        <signup></signup>
    </div>
</template>

<script>
import User from './User.vue'
import Total from './Total.vue'
import Signup from './Signup.vue'
const config = require('./config.js')
window.config = config

export default {
    name: 'app',
    components: { User, Total, Signup },
    data () {
        return {
            title: config.TITLE,
            users: []
        }
    },
    created: function() {
        this.fetchUsers()
    },
    methods: {
        fetchUsers: function() {
            let self = this
            fetch(
               config.API_BASE_URL + '/users/list.php'
            ).then(response => {
                return response.json()
            }).then(json => {
                self.users = json.map(elm => {
                    elm.point = Number(elm.point) 
                    return elm
                })
            });
        }
    },
    computed: {
        total: function() {
            return this.users.reduce(function(prev, current, i, arr) {
                return prev + current.point
            }, 0)
        }
    }
}
</script>

<style>
#app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}

h1, h2 {
    font-weight: normal;
}

h3 {
    width: 300px;
    margin: 10px auto 0 auto;
    text-align: left;
    padding-left: 5px;
}

table {
    width: 300px;
    margin: 0 auto;
}

td {
    text-align: left;
}

a {
    color: #42b983;
}
</style>
