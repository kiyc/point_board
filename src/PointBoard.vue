<template>
    <div id="point_board">
        <div style="margin:10px auto 20px auto;">
            <el-button type="primary">Point</el-button>
            <el-button v-on:click="switchBadgeBoard">Badge</el-button>
        </div>
        <table>
            <user v-for="user in users" v-bind:user="user" v-bind:key="user.name" v-on:openPointDialog="openPointDialog"></user>
        </table>
        <h3>
            <total v-bind:total="total"></total>
        </h3>
    </div>
</template>

<script>
import User from './User.vue'
import Total from './Total.vue'

export default {
    components: { User, Total },
    props: [ 'users' ],
    methods: {
        openPointDialog: function(userId) {
            this.$emit('openPointDialog', userId)
        },
        switchBadgeBoard: function() {
            this.$emit('switchBadgeBoard')
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
