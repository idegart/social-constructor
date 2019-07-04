import BaseBlock from "@model/Blocks/BaseBlock";
import BlockParam from "@model/Blocks/BlockParam";

export default class ReceiveMessage extends BaseBlock {
    constructor(props, block) {
        super(props, block);

        this.color = 'bg-success';
        this.title = 'Message send';
        this.icon = 'fas fa-comment-dots';

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
                label: block.get('data.message') || 'Message',
                connector_id: block.get('data.next_block'),
                cb: this.connectOutParam(),
                cbClick: this.removeNextBlock()
            })
        );
    }

    connectOutParam () {
        return ({end_param}) => {
            this.block.set({data: {next_block: end_param.block.get('id')}});
            this.block.save()
        }
    }

    removeNextBlock () {
        return () => {
            this.block.set({data: {next_block: null}});
            this.block.save()
        }
    }
}
