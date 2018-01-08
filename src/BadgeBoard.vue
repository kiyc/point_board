<template>
    <div id="badge_board">
        <div style="margin:10px auto 20px auto;">
            <el-button v-on:click="switchPointBoard">Point</el-button>
            <el-button type="primary">Badge</el-button>
        </div>
        <table>
            <badge-user v-for="user in users" v-bind:user="user" v-bind:key="user.name" v-on:openBadgeDialog="openBadgeDialog"></badge-user>
        </table>
    </div>
</template>

<script>
import BadgeUser from './BadgeUser.vue'

export default {
    components: { BadgeUser },
    props: [ 'users' ],
    methods: {
        openBadgeDialog: function(userId) {
            this.$emit('openBadgeDialog', userId)
        },
        switchPointBoard: function() {
            this.$emit('switchPointBoard')
        }
    }
}
</script>
