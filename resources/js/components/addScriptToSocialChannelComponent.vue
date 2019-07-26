<template>
    <div>
        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addScriptToSocialChannelModal">
            Add new
            <i class="fas fa-plus"></i>
        </button>

        <div class="modal fade" id="addScriptToSocialChannelModal" tabindex="-1" role="dialog" aria-labelledby="storeScriptModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeScriptModalLabel">Add new script</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitForm" autocomplete="off">
                            <div class="form-group">
                                <label for="script">Select script</label>
                                <select v-model="form.script"
                                        id="script"
                                        class="form-control"
                                        :class="getValidationClass('form.script')">
                                    <option :value="null">Select script</option>
                                    <option v-for="script in scripts" :value="script.id">
                                        {{ script.title }}
                                    </option>
                                </select>
                                <validation-errors :$v="$v" attribute="form.script" />
                            </div>
                            <div class="form-group form-check">
                                <input v-model="form.accept"
                                       type="checkbox"
                                       class="form-check-input"
                                       :class="getValidationClass('form.accept')"
                                       id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                <validation-errors :$v="$v" attribute="form.accept" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button @click="submitForm" type="submit" class="btn btn-primary" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm"
                                  style="vertical-align: sub"
                                  role="status" aria-hidden="true"></span>
                            Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { apiAxios } from '@plugin/axios'
    import validationMixin from "@js/mixins/validationMixin";
    import { required, } from 'vuelidate/lib/validators'
    import { accepted } from '@plugin/validationRules'

    export default {
        name: "addScriptToSocialChannelComponent",

        props: {
            channel: {
                type: Object,
                required: true
            },
            test: {}
        },

        mixins: [validationMixin],

        data: () => ({
            scripts: [],
            loading: false,
            form: {
                script: null,
                accept: false,
            },
        }),

        validations: {
            form: {
                script: {
                    required,
                },
                accept: {
                    required,
                    accepted,
                }
            }
        },

        methods: {
            load () {
                apiAxios.get('/scripts')
                    .then(({data}) => {
                        this.scripts = data.scripts
                    })
            },

            submitForm () {
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return
                }

                apiAxios.post(`/socialChannels/${this.channel.id}/scripts/${this.form.script}`)
                    .then(() => {
                        window.location.reload();
                    });
            },
        },

        mounted () {
            this.load()
        }
    }
</script>

<style scoped>

</style>
