<!-- fieldAwesome.vue -->
<template>
    <div>
        <div class="field-st-upload">
            <div class="ts-upload-input-group">
                <input
                    class="form-control"
                    type="text"
                    v-model="value"
                    :disabled="disabled"
                    :maxlength="schema.max"
                    :placeholder="schema.placeholder"
                    :readonly="schema.readonly" >
                <button @click="openUploader" class="button button-primary"><i class="fa fa-plus"></i></button>
            </div>
            <button v-if="value" @click="clearImage" class="button button-remove"><i class="fa fa-minus"></i></button>
            <div class="ts-media-wrap">
                <img v-if="value" :src="value" alt="">
            </div>
        </div>
        <div class="hint" v-html="hintHtml"></div>
    </div>
</template>

<script>
    import { abstractField } from "vue-form-generator";

    export default {
        mixins: [ abstractField ],
        data(){
            return {
                fileManager:{},
                hintHtml: ""
            }
        },
        created(){
            let id= this.schema.customModel ? 'ts'+this.schema.customModel : 'ts'+this.schema.model;
            let app=this;
            if(typeof wp.media.frames[id] === 'undefined'){
                wp.media.frames[id] = wp.media({
                    title: 'Select image',
                    multiple: false,
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: 'Use selected image'
                    }
                });

                wp.media.frames[id].on('select', function(){
                    var selection =wp.media.frames[id].state().get('selection');

                    // no selection
                    if (!selection) {
                        return;
                    }

                    // iterate through selected elements
                    selection.each(function(attachment) {
                        let url = attachment.attributes.url;
                        app.value=url;
                    });
                });
            }

            if(this.schema.v_hint == 'yes'){
                this.hintHtml = this.schema.desc;
            }

        },
        methods:{
            openUploader()
            {
                let id=this.schema.customModel ? 'ts'+this.schema.customModel : 'ts'+this.schema.model;
                if(wp.media.frames[id]) {
                    wp.media.frames[id].open();
                    return;
                }
            },
            clearImage(){
                //this.$emit('value','');
                this.value='';
            }
        }
    };
</script>
