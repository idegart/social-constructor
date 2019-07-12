import connectorMutations from './connector/mutations'
import fieldMutations from './field/mutations'

export default {
    ...connectorMutations,
    ...fieldMutations,

    setScript: (state, script) => state.script = script,
    clearScript: state => state.script = null,

    addSchema: (state, schema) => state.schemas.push(schema),
    clearSchemas: state => state.schemas = [],

    setSchema: (state, schema) => state.schema = schema,
    clearShema: state => state.schema = null,

    addBlock: (state, block) => state.blocks.push(block),
    clearBlocks: state => state.blocks = [],
}
