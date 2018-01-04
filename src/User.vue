<template>
    <tr>
        <td>{{ user.name }}: {{ user.point }}</td>
        <td>
            <el-button icon="el-icon-plus" v-on:click="openDialog"></el-button>
            <el-dialog
                title="Please input point and password"
                :visible.sync="dialogVisible"
                width="30%"
                :before-close="closeDialog">
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
                    <el-button v-on:click="closeDialog">Cancel</el-button>
                    <el-button type="primary" v-on:click="updatePoint">Submit</el-button>
                </span>
            </el-dialog>
        </td>
    </tr>
</template>

<script>
import { Message } from 'element-ui'
export default {
    props: ['user'],
    data () {
        return {
            point: 0,
            dialogVisible: false,
            password: ''
        }
    },
    methods: {
        openDialog: function() {
            this.dialogVisible = true
        },
        updatePoint: function() {
            const point = Number(this.point)

            if (!point) return

            let formdata = new FormData()
            formdata.append('id', this.user.id)
            formdata.append('password', this.password)
            formdata.append('point', point)

            this.closeDialog()

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
                // fetch user
                fetch(
                    config.API_BASE_URL + '/users/first.php?id=' + this.user.id
                ).then(response => {
                    return response.json()
                }).then(json => {
                    this.user.point = Number(json.point)
                    Message({
                        showClose: true,
                        message: 'Success update point.',
                        type: 'success',
                    })
                })
            }).catch(error => {
                Message({
                    showClose: true,
                    message: '' + error,
                    type: 'error'
                })
            })
        },
        closeDialog: function() {
            this.point = 0
            this.password = ''
            this.dialogVisible = false
        }
    }
}
</script>

<style>
.input_point {
    width: 80px;
}
</style>
