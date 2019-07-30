import connectorMutations from './connector/mutations'
import fieldMutations from './field/mutations'
import blockModalsMutations from './blockModals/mutations'

export default {
    ...connectorMutations,
    ...fieldMutations,
    ...blockModalsMutations,

    setScript: (state, script) => state.script = script,
    clearScript: state => state.script = null,

    addSchema: (state, schema) => state.schemas.push(schema),
    clearSchemas: state => state.schemas = [],

    setSchema: (state, schema) => state.schema = schema,
    clearShema: state => state.schema = null,

    addBlock: (state, block) => state.blocks.push(block),
    clearBlocks: state => state.blocks = [],

    setVariables: (state, variables) => state.variables = variables,
    setExternalApi: (state, externalAPI) => state.externalAPI = externalAPI,

    setFPS: (state, fps) => state.fps = Number(fps),
    setStatsVisible: (state, statsVisible) => state.statsVisible = statsVisible,
}
