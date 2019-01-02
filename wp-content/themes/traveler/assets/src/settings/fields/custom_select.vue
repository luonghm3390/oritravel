<template>
    <div>
        <select v-model="value" class="form-control">
            <option :value="item.value" :selected="(item.value === value)" v-for="(item,index) in schema.choices">{{item.label}}</option>
        </select>
        <div class="hint" v-html="hintHtml"></div>
    </div>
</template>

<script>
    import { abstractField } from "vue-form-generator";

    export default {
        mixins: [ abstractField ],
        data(){
            return {
                hintHtml: ""
            }
        },
        created(){
            if(this.value == "") {
                if (this.schema.std != null) {
                    this.value = this.schema.std;
                }
            }
            if(this.schema.v_hint == 'yes'){
                this.hintHtml = this.schema.desc;
            }
        }
    };
</script>