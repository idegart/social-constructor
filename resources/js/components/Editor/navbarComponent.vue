<template>
    <hsc-menu-style-white style="position: fixed;z-index: 9999">
        <hsc-menu-bar style="border-radius: 0 0 4pt 0;">
            <hsc-menu-bar-item label="File">
                <hsc-menu-item label="New" @click="alert('New')" />
                <hsc-menu-item label="Open" @click="alert('Open')" />
                <hsc-menu-separator />
                <hsc-menu-item label="Load schema">
                    <hsc-menu-item v-for="schema in schemas"
                                   :label="schema.title"
                                   :key="schema.id" />
                </hsc-menu-item>
                <hsc-menu-separator />
                <hsc-menu-item label="Exit" @click="toExit" />
            </hsc-menu-bar-item>
            <hsc-menu-bar-item label="Options">
                <hsc-menu-item label="Open params" @click="openModal('modalParams')" />
            </hsc-menu-bar-item>
            <hsc-menu-bar-item label="Settings">
                <hsc-menu-item label="Statistic" v-model="statsVisibleToggler" />
                <hsc-menu-item>
                    <div slot="body" class="d-flex" @mousedown.stop>
                        FPS:
                        <input v-model="fpsToggler"
                               class="ml-1"
                               min="20"
                               max="60"
                               step="10"
                               style="width: 100px"
                               type="range" @mousedown.stop />
                    </div>
                </hsc-menu-item>
            </hsc-menu-bar-item>
            <hsc-menu-bar-item label="Edit">
                <hsc-menu-item label="Undo" keybind="meta+z" @click="alert('Undo')" />
                <hsc-menu-separator/>
                <hsc-menu-item label="Cut" keybind="meta+x" @click="alert('Cut')" />
                <hsc-menu-item label="Copy" keybind="meta+c" @click="alert('Copy')" />
                <hsc-menu-item label="Paste" keybind="meta+v" @click="alert('Paste')" :disabled="true" />
            </hsc-menu-bar-item>
        </hsc-menu-bar>
    </hsc-menu-style-white>
</template>

<script>
    import { createNamespacedHelpers } from 'vuex'

    const {mapGetters, mapMutations, mapState} = createNamespacedHelpers('editor')

    export default {
        name: "navbarComponent",

        computed: {
            ...mapGetters([
                'script',
                'schemas',
            ]),
            ...mapState([
                'statsVisible',
                'fps',
            ]),

            statsVisibleToggler: {
                get () {
                    return this.statsVisible
                },
                set (value) {
                    this.setStatsVisible(value)
                }
            },
            fpsToggler: {
                get () {
                    return this.fps
                },
                set (value) {
                    this.setFPS(value)
                },
            }
        },

        methods: {
            ...mapMutations([
                'setFPS',
                'setStatsVisible',
            ]),
            alert () {},
            toExit () {
                window.location.href = `/scripts/${this.script.id}`
            },

            openModal (modalId) {
                $(`#${modalId}`).modal('toggle')
            }
        }
    }
</script>

<style scoped>

</style>
