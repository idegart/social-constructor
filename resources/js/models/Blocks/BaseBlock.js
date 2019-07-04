import Vue from 'vue'
import {uniqueId} from "lodash";

export default class BaseBlock {
    constructor(props, block) {
        this.block = block;

        Vue.set(this, 'block', block);

        Vue.set(this, 'paramsOut', []);
        Vue.set(this, 'paramsIn', []);

        Vue.set(this, 'id', uniqueId('block_'));
        Vue.set(this, 'z', 0);

        Vue.set(this, 'color', 'bg-primary');
        Vue.set(this, 'title', 'Base block');
        Vue.set(this, 'icon', '');
    }
}
