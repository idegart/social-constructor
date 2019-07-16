import Vue from 'vue'
import { extend, isEmpty, has, get } from 'lodash'
import { apiAxios } from "@plugin/axios"
import {Model, Collection} from 'vue-mc'

export default class Variable extends Model {
    constructor(props) {
        super(props);
    }

    routes() {
        return {
            fetch: '/scripts/{script_id}/variables/{id}',
        }
    }

    getRequest(config) {
        config = extend( config, apiAxios.defaults );
        return super.getRequest(config);
    }

    onFetchSuccess(response) {
        let attributes = response.getData();

        if (isEmpty(attributes) || !has(attributes, 'script')) {
            throw new Error("No data in fetch response")
        }

        this.assign(get(attributes, 'script'));

        Vue.set(this, 'fatal',   false);
        Vue.set(this, 'loading', false);

        this.emit('fetch', {error: null});
    }
}
