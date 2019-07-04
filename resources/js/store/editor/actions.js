import { merge, throttle } from 'lodash'
import {getRealPosition} from '../index'
import Script from "@model/Script";
import {SchemaCollection} from "@model/Schema";
import Block, {BlockCollection} from "@model/Block";
import BlockParam from "@model/Blocks/BlockParam";

export default {
    initializeEditorData: ({state, dispatch, commit}, scriptId) => {
        return new Promise((resolve, reject) => {
            dispatch('loadScript', scriptId)
                .then(({script}) => dispatch('loadSchemas', script.get('id')))
                .then(() => {
                    let schema = state.schemas.find(schema => schema.id === state.script.get('starter_schema_id'));
                    commit('setSchema', schema)
                })
                .then(() => dispatch('loadBlocks', state.schema.get('id')))
                .catch(error => {
                    reject(error)
                })
                .finally(() => {
                    resolve()
                })
        })
    },

    loadScript: ({commit}, scriptId) => {
        return new Promise((resolve, reject) => {
            commit('clearScript');

            let script  = new Script({id: scriptId});

            script.fetch()
                .then( () => {
                    commit('setScript', script);
                    resolve({script})
                })
                .catch(() => {
                    reject()
                })
        })
    },

    loadSchemas: ({state, commit}, scriptId) => {
        return new Promise((resolve, reject) => {
            commit('clearSchemas');

            let schemaCollection = new SchemaCollection();

            schemaCollection.set('scriptId', scriptId);

            schemaCollection.fetch()
                .then(() => {
                    let schemas = [];
                    schemaCollection.models.forEach(schema => {
                        commit('addSchema', schema);
                        schemas.push(schema)
                    });

                    resolve({schemas})
                })
                .catch(() => {
                    reject()
                })
        })
    },

    loadBlocks: ({state, commit}, schemaId) => {
        return new Promise((resolve, reject) => {
            commit('clearBlocks');

            let blockCollection = new BlockCollection();

            blockCollection.set('schemaId', schemaId);

            blockCollection.fetch()
                .then(() => {
                    let blocks = [];

                    blockCollection.models.forEach(block => {
                        commit('addBlock', block);
                        blocks.push(block)
                    });

                    resolve({blocks})
                })
                .catch(() => {
                    reject()
                })
        })
    },

    storeBlock: ({state, commit, getters}, payload) => {
        Block.storeBlock(merge({schema_id: state.schema.id}, payload))
            .then(block => {
                commit('addBlock', block)
            })
    },

    removeBlock: ({commit}, block) => {
        block.delete()
    },

    renderConnections: throttle(({state}) => {
        if (!state.connectionsLayer) {
            return
        }

        state.connectionsLayer.removeChildren();

        let paramsIn = Array.from(document.querySelectorAll(`[data-param-type="${BlockParam.TYPE_IN}"]`)),
            paramsOut = Array.from(document.querySelectorAll(`[data-param-type="${BlockParam.TYPE_OUT}"]`));

        paramsOut.forEach(paramOut => {
            let OutDataset = paramOut.dataset,
                OutParamConnectorId = OutDataset.paramConnectorId,
                OutParamConnectorType = OutDataset.paramConnectorType;

            let connectParam = paramsIn.find(paramIn => {
                let InDataset = paramIn.dataset,
                    InParamConnectorId = InDataset.paramConnectorId,
                    InParamConnectorType = InDataset.paramConnectorType;

                return OutParamConnectorId === InParamConnectorId
                    && OutParamConnectorType === InParamConnectorType
            });

            if (!connectParam) {
                return
            }

            let paramPosition = getRealPosition(paramOut),
                connectorPosition = getRealPosition(connectParam);

            let line = new Konva.Line({
                points: [
                    paramPosition.left, paramPosition.top,
                    connectorPosition.left, connectorPosition.top
                ],
                stroke: 'white',
                strokeWidth: 3,
                lineCap: 'round',
                lineJoin: 'round'
            });

            state.connectionsLayer.add(line)
        });

        state.connectionsLayer.draw()

    }, 10)
}
