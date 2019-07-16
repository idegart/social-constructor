import BaseBlock from "@model/Blocks/BaseBlock";
import BlockParam from "@model/Blocks/BlockParam";

export default class ParamCompare extends BaseBlock {
    constructor(props, block) {
        super(props, block);

        this.color = 'bg-primary';
        this.title = 'Param compare';
        this.icon = 'fas fa-not-equal';

        this.paramsIn.push(
            new BlockParam({
                block: block,
                label: 'IN',
                connector_id: block.get('id'),
                type: BlockParam.TYPE_IN
            })
        );

        this.paramsOut.push(
            new BlockParam({
                block: block,
                label: 'TRUE',
                connector_id: block.get('data.true_next_block_id'),
                cb: this.connectOutParam('true_next_block_id'),
                cbClick: this.removeNextBlock('true_next_block_id'),
                extraClass: 'text-success'
            })
        );

        this.paramsOut.push(
            new BlockParam({
                block: block,
                label: 'FALSE',
                connector_id: block.get('data.false_next_block_id'),
                cb: this.connectOutParam('false_next_block_id'),
                cbClick: this.removeNextBlock('false_next_block_id'),
                extraClass: 'text-danger',
            })
        );
    }

    connectOutParam (next_block_key) {
        return ({end_param}) => {
            let data = {}
            data[next_block_key] = end_param.block.get('id')
            this.block.set({data});
            this.block.save()
        }
    }

    removeNextBlock (next_block_key) {
        return () => {
            let data = {}
            data[next_block_key] = null
            this.block.set({data});
            this.block.save()
        }
    }
}
