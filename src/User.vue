<template>
    <tr>
        <td>{{ user.name }}: {{ user.point }}</td>
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
            this.$emit('openPointDialog', this.user.id)
        }
    }
}
</script>

<style>
</style>
