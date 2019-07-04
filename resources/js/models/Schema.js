import Vue from 'vue'
import {extend, isEmpty, has, get,} from 'lodash'
import { apiAxios } from "@plugin/axios"
import {Model, Collection} from 'vue-mc'

export default class Schema extends Model {
    routes() {
        return {
            fetch: '/schemas/{id}',
        }
    }

    getRequest(config) {
        config = extend( config, apiAxios.defaults );
        return super.getRequest(config);
    }

    onFetchSuccess(response) {
        let attributes = response.getData();

        if (isEmpty(attributes) || !has(attributes, 'schema')) {
            throw new Error("No data in fetch response")
        }

        this.assign(get(attributes, 'schema'));

        Vue.set(this, 'fatal',   false);
        Vue.set(this, 'loading', false);

        this.emit('fetch', {error: null});
    }
}

export class SchemaCollection extends Collection {
    options() {
        return {
            model: Schema,
        }
    }

    routes() {
        return {
            fetch: '/scripts/{scriptId}/schemas',
        }
    }

    getRequest(config) {
        config = extend( config, apiAxios.defaults );
        return super.getRequest(config);
    }

    getModelsFromResponse(response) {
        let attributes = response.getData();

        if (!has(attributes, 'schemas')) {
            return null;
        }

        if (this.isPaginated()) {
            return get(attributes, 'data', attributes);
        }

        return get(attributes, 'schemas', attributes);
    }
}

