import BaseBlock from "@model/Blocks/BaseBlock";
import BlockParam from "@model/Blocks/BlockParam";

export default class ParamAdd extends BaseBlock {
    constructor(props, block) {
        super(props, block);

        this.color = 'bg-primary';
        this.title = 'Param add';
        this.icon = 'fas fa-plus-square';

        // this.paramsOut.push(
        //     new BlockParam({
        //         block: block,
        //         label: block.get('data.message') || 'Message',
        //         connector_id: block.get('data.next_block_id'),
        //         cb: this.connectOutParam(),
        //         cbClick: this.removeNextBlock()
        //     })
        // );
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
}
