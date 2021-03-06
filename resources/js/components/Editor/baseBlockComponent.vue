<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <vue-draggable-resizable parent="#editorField"
                             :x="block.get('left')" :y="block.get('top')"
                             :w="1" :h="1"
                             @activated="activated"
                             @deactivated="deactivated"
                             @dragstop="dragstop"
                             @dragging="dragging"
                             dragCancel=".unhandle,.param">
        <div class="block-card" :class="{active: isActive}">
            <div class="block-header d-flex p-1 align-items-center" :class="get(blockClass, 'color')">
                <div class="px-1">
                    <slot name="icon">
                        <i :class="get(blockClass, 'icon')"></i>
                    </slot>
                </div>
                <div class="flex-grow-1">
                    <slot name="title">
                        {{ get(blockClass, 'title') }}
                    </slot>
                </div>
                <div>
                    <div class="dropdown unhandle">
                        <button class="btn btn-light btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button v-if="editable" @click.prevent="$emit('toEdit')"
                                    class="dropdown-item">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </button>
                            <slot name="dropdown"></slot>
                            <div v-if="editable" class="dropdown-divider"></div>
                            <button @click.prevent="remove(block)"
                                    class="dropdown-item">
                                <i class="fas fa-trash mr-1"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block-body d-flex no-gutters p-1 justify-content-between">
                <div class="w-auto">
                    <slot name="params-in">
                        <block-param-component v-for="param in get(blockClass,'paramsIn', [])"
                                               :key="param._id"
                                               :param="param">
                            <template v-slot:param="{param}">
                                <i :class="param.getIcon()"
                                   @mouseup.prevent="param.handleEndPoint()"
                                   :data-param-type="param.type"
                                   :data-param-connector-id="param.connector.id"
                                   :data-param-connector-type="param.connector.type"
                                ></i>
                            </template>

                            <template v-slot:label="{param}">
                                <span>{{ param.label }}</span>
                            </template>

                        </block-param-component>
                    </slot>
                </div>
                <div class="w-auto">
                    <slot name="params-out">
                        <block-param-component v-for="param in get(blockClass,'paramsOut', [])"
                                               :key="param._id"
                                               :param="param"
                                               out>
                            <template v-slot:param="{param}">
                                <i :class="param.getIcon()"
                                   @mousedown.prevent="param.handleStartPoint($event)"
                                   @click="param.handleOutClickEvent($event)"
                                   :data-param-type="param.type"
                                   :data-param-connector-id="param.connector.id"
                                   :data-param-connector-type="param.connector.type"
                                ></i>
                            </template>

                            <template v-slot:label="{param}">
                                <span>{{ param.label }}</span>
                            </template>

                            <template v-if="isRemovable(param)" v-slot:removable="{param}">
                                <i @click="param.cbRemove()" class="fas fa-trash text-danger unhandle" style="cursor: pointer"></i>
                            </template>

                        </block-param-component>
                    </slot>
                </div>
            </div>

            <div v-if="needFooter" class="block-footer">
                <slot name="footer" />
            </div>
        </div>
    </vue-draggable-resizable>
</template>

<script>
    import {get} from 'lodash'
    import { createNamespacedHelpers } from 'vuex'
    const { mapActions } = createNamespacedHelpers('editor');

    import VueDraggableResizable from 'vue-draggable-resizable'

    import Block from '@model/Block'
    import BaseBlock from "@model/Blocks/BaseBlock";
    import blockParamComponent from "@component/Editor/blockParamComponent";

    export default {
        name: "baseBlockComponent",

        components: {
            blockParamComponent,
            VueDraggableResizable,
        },

        props: {
            block: Block,
            blockClass: BaseBlock,
            editable: {
                type: Boolean,
                default: false,
                required: false,
            },
            needFooter: {
                type: Boolean,
                default: false,
                required: false,
            },
        },

        data: () => ({
            isActive: false,
        }),

        methods: {
            get,
            ...mapActions([
                'removeBlock',
            ]),

            dragging () {
            },

            dragstop (left, top) {
                this.block.set('left', left);
                this.block.set('top', top);
                this.block.save()
            },

            activated () {
                this.isActive = true;
            },

            deactivated () {
                this.isActive = false;
            },

            remove(block) {
                this.removeBlock(block)
            },

            isRemovable (param) {
                return typeof param.cbRemove === 'function'
            }
        },
    }
</script>

<style scoped>

</style>
