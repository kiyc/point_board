<template>
    <tr>
        <td>{{ user.name }}: {{ user.point }}</td>
        <td>
            <input v-model="point" placeholder="0">
            <button v-on:click="updatePoint">+</button>
        </td>
    </tr>
</template>

<script>
export default {
    props: ['user'],
    data () {
        return {
            point: 0
        }
    },
    methods: {
        updatePoint: function() {
            const point = Number(this.point)
            this.point = 0

            if (!point) return
            if (!confirm(this.user.name + ' +' + point + ' OK?')) return

            let formdata = new FormData()
            formdata.append('id', this.user.id)
            formdata.append('point', point)
            let user = this.user

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
                    config.API_BASE_URL + '/users/first.php?id=' + user.id
                ).then(response => {
                    return response.json()
                }).then(json => {
                    user.point = Number(json.point)
                })
            }).catch(error => {
                alert(error)
            })
        }
    }
}
</script>

<style>
input {
    width: 40px;
}

button {
    width: 40px;
    height: 20px;
    margin-bottom: 2px;
    line-height: 10px;
    text-align: center;
    vertical-align: middle;
    overflow: hidden;
    transition: .4s;
}
</style>
