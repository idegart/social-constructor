<template>
    <div>
        <button @click="removeScript" class="btn btn-outline-danger">Remove</button>
    </div>
</template>

<script>
    import { apiAxios } from '@plugin/axios'

    export default {
        name: "removeScriptFromSocialChannel",

        props: {
            channel: {
                type: Object,
                required: true,
            },
            script: {
                type: Object,
                required: true,
            },
        },

        methods: {
            removeScript () {
                apiAxios.delete(`/socialChannels/${this.channel.id}/scripts/${this.script.id}`)
                    .then(() => {
                        window.location.reload();
                    })
            }
        },
    }
</script>

<style scoped>

</style>
