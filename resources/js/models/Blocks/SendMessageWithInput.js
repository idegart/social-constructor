import BaseBlock from "@model/Blocks/BaseBlock";
import BlockParam from "@model/Blocks/BlockParam";

export default class SendMessageWithInput extends BaseBlock {
    constructor(props, block) {
        super(props, block);

        this.color = 'bg-success';
        this.title = 'Message w/input';
        this.icon = 'fas fa-keyboard';

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
                connector_id: block.get('data.next_block_id'),
                cb: this.connectOutParam(),
                cbClick: this.removeNextBlock()
            })
        );

        this.paramsOut.push(
            new BlockParam({
                block: block,
                label: 'ON ERROR',
                connector_id: block.get('data.error_next_block_id'),
                cb: this.connectOutErrorParam(),
                cbClick: this.removeOutErrorParam(),
                extraClass: 'text-danger'
            })
        );
    }

    connectOutParam () {
        return ({end_param}) => {
            this.block.set({data: {next_block_id: end_param.block.get('id')}});
            this.block.save()
        }
    }

    removeNextBlock () {
        return () => {
            this.block.set({data: {next_block_id: null}});
            this.block.save()
        }
    }

    connectOutErrorParam () {
        return ({end_param}) => {
            this.block.set({data: {error_next_block_id: end_param.block.get('id')}});
            this.block.save()
        }
    }

    removeOutErrorParam () {
        return () => {
            this.block.set({data: {error_next_block_id: null}});
            this.block.save()
        }
    }
}
