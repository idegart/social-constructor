import Vue from 'vue'
import {extend, isEmpty, has, get, upperFirst, camelCase} from 'lodash'
import { apiAxios } from "@plugin/axios"
import {Model, Collection} from 'vue-mc'

export default class Block extends Model {
    constructor(props) {
        super(props);

        if (has(props, 'data')) {
            this.setBlockDataClass(props)
        }
    }

    setBlockDataClass(props) {

        this.set('_blockClass', null);

        let blockDataClassName = upperFirst(camelCase(get(props, 'data_type')));

        import(`./Blocks/${blockDataClassName}.js`)
            .then(result => {
                let blockClass = result.default;

                this.set('_blockClass', new blockClass(props, this));
            })
    }

    routes() {
        return {
            fetch: '/blocks/{id}',
            save:  '/blocks',
            patch: '/blocks/{id}',
            delete: '/blocks/{id}',
        }
    }

    getRequest(config) {
        config = extend( config, apiAxios.defaults );
        return super.getRequest(config);
    }

    onFetchSuccess(response) {
        let attributes = response.getData();

        if (isEmpty(attributes) || !has(attributes, 'block')) {
            throw new Error("No data in fetch response")
        }

        this.assign(get(attributes, 'block'));

        Vue.set(this, 'fatal',   false);
        Vue.set(this, 'loading', false);

        this.emit('fetch', {error: null});
    }

    onSaveSuccess(response) {
        this.clearErrors();

        if (response) {
            let attributes = response.getData();
            let blockData = get(attributes, 'block', attributes);

            this.update(blockData);
            this.setBlockDataClass(blockData)
        }

        Vue.set(this, 'saving', false);
        Vue.set(this, 'fatal', false);

        this.addToAllCollections();
        this.emit('save', {
            error: null
        });
    }

    static storeBlock (payload) {
        return new Promise((resolve, reject) => {
            let block = (new Block(payload));

            block.save()
                .then(() => {
                    resolve(block)
                })
                .catch(error => {
                    reject(error)
                })
        })
    }

    shouldPatch () {
        return true
    }

    changed () {
        let changed = super.changed()
        changed.splice(changed.indexOf('_blockClass'), 1);
        return changed
    }
}

export class BlockCollection extends Collection {
    options() {
        return {
            model: Block,
        }
    }

    routes() {
        return {
            fetch: '/schemas/{schemaId}/blocks',
        }
    }

    getRequest(config) {
        config = extend( config, apiAxios.defaults );
        return super.getRequest(config);
    }

    getModelsFromResponse(response) {
        let attributes = response.getData();

        if (!has(attributes, 'blocks')) {
            return null;
        }

        if (this.isPaginated()) {
            return get(attributes, 'data', attributes);
        }

        return get(attributes, 'blocks', attributes);
    }
}

