<template>
    <div id="editorContainer" class="vh-100 vw-100" style="overflow: scroll">
        <div v-if="dataLoading" class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-grow text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <field-component v-else></field-component>

    </div>
</template>

<script>
    import editorStore from '@store/editor'

    import {createNamespacedHelpers} from 'vuex'
    import FieldComponent from "@component/Editor/fieldComponent";

    const {mapActions, mapGetters} = createNamespacedHelpers('editor');

    export default {
        name: "editorScriptComponent",
        components: {FieldComponent},
        props: {
            scriptId: {
                type: Number,
                required: true,
            },
        },

        data: () => ({
            dataLoading: true,
        }),

        computed: {
            ...mapGetters([
                'getInitialized',
            ]),
        },

        methods: {
            ...mapActions([
                'initializeEditorData',
            ]),
        },

        mounted() {
            this.initializeEditorData(this.scriptId)
                .then(() => {
                    this.dataLoading = false
                })
        },

        beforeCreate () {
            this.$store.registerModule('editor', editorStore);
        },

        beforeDestroy() {
            this.$store.unregisterModule('editor')
        }
    }
</script>

<style scoped>

</style>
