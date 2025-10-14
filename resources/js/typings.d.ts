
declare module '*.vue' {
    import Vue from 'vue';
    export default Vue;
};

declare module "jquery" {
    export = $;
}

interface JQueryStatic {
    gritter: any;
}

declare var $: JQueryStatic;

declare module 'lodash';

//declare module 'vue-toastr-2';
