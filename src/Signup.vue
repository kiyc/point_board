<template>
    <div id="signup">
        <el-button v-on:click="openDialog">Sign up</el-button>
        <el-dialog
            title="Sing up with name and password"
            :visible.sync="dialogVisible"
            width="30%"
            :before-close="closeDialog">
            <table>
                <tr>
                    <td>Name</td>
                    <td><el-input v-model="name" type="text"></el-input></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><el-input v-model="password" type="password" class="password"></el-input></td>
                </tr>
            </table>
            <span slot="footer" class="dialog-footer">
                <el-button v-on:click="closeDialog">Cancel</el-button>
                <el-button type="primary" v-on:click="signupUser">Submit</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
import { Message } from 'element-ui'
export default {
    data () {
        return {
            name: '',
            password: '',
            dialogVisible: false
        }
    },
    methods: {
        openDialog: function() {
            this.dialogVisible = true
        },
        signupUser: function() {
            if (!this.name.length || !this.password.length) {
                Message({
                    showClose: true,
                    message: 'Invalid input value.',
                    type: 'error'
                })
                return
            }

            let formdata = new FormData()
            formdata.append('name', this.name)
            formdata.append('password', this.password)

            fetch(
                // sign up user
                config.API_BASE_URL + '/users/signup.php',
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
                this.$emit('fetchUsers')
                Message({
                    showClose: true,
                    message: 'Success sign up your account.',
                    type: 'success',
                })
                this.closeDialog()
            }).catch(error => {
                Message({
                    showClose: true,
                    message: 'Error on sign up your account.',
                    type: 'error'
                })
            })
        },
        closeDialog: function() {
            this.name = ''
            this.password = ''
            this.dialogVisible = false
        }
    }
}
</script>

<style>
#signup {
    width: 300px;
    margin: 20px auto 0 auto;
    text-align: left;
}
</style>
