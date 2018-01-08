<template>
    <tr>
        <td>
            {{ user.name }}
            <span v-for="badge in user.badges" v-bind:badge="badge" v-bind:key="badge.id">
                <span v-html="badge.src"></span>
            </span>
        </td>
        <td>
            <el-button icon="el-icon-plus" v-on:click="openDialog"></el-button>
        </td>
    </tr>
</template>

<script>
export default {
    props: ['user'],
    methods: {
        openDialog: function() {
            this.$emit('openBadgeDialog', this.user.id)
        }
    }
}
</script>

<style>
</style>
