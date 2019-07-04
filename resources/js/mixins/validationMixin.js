import {get} from 'lodash'

import validationErrors from '@js/shared/validationErrors'

export default {
    components: {
        validationErrors,
    },

    methods: {
        getValidationClass (attribute) {
            let v = get(this.$v, attribute);

            if (!v || !v.$dirty) {
                return
            }

            return [v.$invalid ? 'is-invalid' : 'is-valid']
        },
    }
}
