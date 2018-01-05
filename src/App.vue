<template>
    <div id="app">
        <h1>{{ title }}</h1>
        <table>
            <user v-for="user in users" v-bind:user="user" v-bind:key="user.name" v-on:openPointDialog="openPointDialog"></user>
        </table>
        <h3>
            <total v-bind:total="total"></total>
        </h3>
            <el-dialog
                title="Please input point and password"
                :visible.sync="pointDialogVisible"
                width="30%"
                :before-close="closePointDialog">
                <table>
                    <tr>
                        <td>Point</td>
                        <td><el-input v-model="point" type="text" placeholder="0" class="input_point"></el-input></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><el-input v-model="password" type="password" class="password"></el-input></td>
                    </tr>
                </table>
                <span slot="footer" class="dialog-footer">
                    <el-button v-on:click="closePointDialog">Cancel</el-button>
                    <el-button type="primary" v-on:click="updatePoint">Submit</el-button>
                </span>
            </el-dialog>
        <signup v-on:fetchUsers="fetchUsers"></signup>
    </div>
</template>

<script>
import User from './User.vue'
import Total from './Total.vue'
import Signup from './Signup.vue'
import { Message } from 'element-ui'
const config = require('./config.js')
window.config = config

export default {
    name: 'app',
    components: { User, Total, Signup },
    data () {
        return {
            title: config.TITLE,
            users: [],
            pointDialogVisible: false,
            targetUserId: 0,
            point: 0,
            password: ''
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
        },
        openPointDialog: function(userId) {
            this.targetUserId = userId,
            this.pointDialogVisible = true
        },
        closePointDialog: function() {
            this.targetUserId = 0
            this.point = 0
            this.password = ''
            this.pointDialogVisible = false
        },
        updatePoint: function() {
            const point = Number(this.point)

            if (!point) return

            let formdata = new FormData()
            formdata.append('id', this.targetUserId)
            formdata.append('password', this.password)
            formdata.append('point', point)

            this.closePointDialog()

            fetch(
                // update point
                config.API_BASE_URL + '/users/update.php',
                { method: 'POST', body: formdata }
            ).then(response => {
                return response.text()
            }).then(text => {
                const status = Number(text)
                if (isNaN(status) || status) {
                    throw new Error(text)
                }
                return status
            }).then(status => {
                // fetch users
                this.fetchUsers()
                Message({
                    showClose: true,
                    message: 'Success update point.',
                    type: 'success',
                })
            }).catch(error => {
                Message({
                    showClose: true,
                    message: '' + error,
                    type: 'error'
                })
            })
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

.input_point {
    width: 80px;
}
</style>
