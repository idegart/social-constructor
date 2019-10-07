import BaseBlock from "@model/Blocks/BaseBlock";
import BlockParam from "@model/Blocks/BlockParam";

export default class FilterChannel extends BaseBlock {
    constructor(props, block) {
        super(props, block);

        this.color = 'bg-primary';
        this.title = 'Channel filter';
        this.icon = 'fas fa-filter';

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
                label: 'Channels',
                connector_id: block.get('data.channels_next_block_id'),
                cb: this.connectOutParam('channels_next_block_id'),
                cbClick: this.removeNextBlock('channels_next_block_id'),
            })
        );

        this.paramsOut.push(
            new BlockParam({
                block: block,
                label: 'Other',
                connector_id: block.get('data.other_next_block_id'),
                cb: this.connectOutParam('other_next_block_id'),
                cbClick: this.removeNextBlock('other_next_block_id'),
                extraClass: 'text-danger',
            })
        );
    }

    connectOutParam (key) {
        return ({end_param}) => {
            let data = {};
            data[key] = end_param.block.get('id');

            this.block.set({data});
            this.block.save()
        }
    }

    removeNextBlock (key) {
        return () => {
            let data = {};
            data[key] = null;

            this.block.set({data});
            this.block.save()
        }
    }
}
