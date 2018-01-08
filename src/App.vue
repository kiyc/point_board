<template>
    <div id="app">
        <h1>{{ title }}</h1>
        <point-board
            v-show="pointBoardVisible"
            v-bind:users="users"
            v-on:openPointDialog="openPointDialog"
            v-on:switchBadgeBoard="switchBadgeBoard">
        </point-board>
        <badge-board
            v-show="badgeBoardVisible"
            v-bind:users="users"
            v-on:openBadgeDialog="openBadgeDialog"
            v-on:switchPointBoard="switchPointBoard">
        </badge-board>
        <el-dialog
            title="Input point and password"
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
        <el-dialog
            title="Select badge and input password"
            :visible.sync="badgeDialogVisible"
            width="30%"
            :before-close="closeBadgeDialog">
            <table>
                <tr>
                    <td></td>
                    <td>
                        <span v-for="(badge, index) in badges" v-bind:badge="badge" v-bind:key="badge.id">
                            <label>
                                <input type="checkbox" :value="badge.id" v-model="checkedBadges">
                                <span v-html="badge.src"></span>
                            </label>
                            <br v-if="index % 3 == 2">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><el-input v-model="password" type="password" class="password"></el-input></td>
                </tr>
            </table>
            <span slot="footer" class="dialog-footer">
                <el-button v-on:click="closeBadgeDialog">Cancel</el-button>
                <el-button type="primary" v-on:click="updateBadge">Submit</el-button>
            </span>
        </el-dialog>
        <signup v-on:fetchUsers="fetchUsers"></signup>
    </div>
</template>

<script>
import PointBoard from './PointBoard.vue'
import BadgeBoard from './BadgeBoard.vue'
import Signup from './Signup.vue'
import { Message } from 'element-ui'

export default {
    name: 'app',
    components: { PointBoard, BadgeBoard, Signup },
    data () {
        return {
            title: config.TITLE,
            users: [],
            badges: [],
            pointBoardVisible: true,
            badgeBoardVisible: false,
            pointDialogVisible: false,
            badgeDialogVisible: false,
            targetUserId: 0,
            point: 0,
            password: '',
            checkedBadges: [],
        }
    },
    created: function() {
        this.fetchUsers()
        this.fetchBadges()
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
        fetchBadges: function() {
            let self = this
            fetch(
               config.API_BASE_URL + '/badges/list.php'
            ).then(response => {
                return response.json()
            }).then(json => {
                self.badges = json
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
        },
        switchPointBoard: function() {
            this.badgeBoardVisible = false
            this.pointBoardVisible = true
        },
        switchBadgeBoard: function() {
            this.pointBoardVisible = false
            this.badgeBoardVisible = true
        },
        openBadgeDialog: function(userId) {
            this.targetUserId = userId,
            this.badgeDialogVisible = true
        },
        closeBadgeDialog: function() {
            this.targetUserId = 0
            this.checkedBadges = []
            this.password = ''
            this.badgeDialogVisible = false
        },
        updateBadge: function() {
            let formdata = new FormData()
            formdata.append('id', this.targetUserId)
            formdata.append('password', this.password)
            formdata.append('badges', this.checkedBadges)

            this.closeBadgeDialog()

            fetch(
                // update user badges
                config.API_BASE_URL + '/badges/update.php',
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
                    message: 'Success update user badges.',
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
