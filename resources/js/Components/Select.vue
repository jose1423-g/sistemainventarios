<script setup>

import { onMounted, ref } from 'vue';

const model = defineModel({
    type: [String, Number],
    required: false,
});

defineProps({
    data:{
        type: Object,
        required: false,
        default: [],
    },
    label: {
        type: String,
        required: false,
    },
    text: {
        type: String,
        required: false,
    }
})

const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute('autofocus')) {
        select.value.focus();
    }
});

defineExpose({ focus: () => select.value.focus() });

</script>
<template>
    <select 
        class="p-1 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="select"
    >
        <option value="">Selecciona</option>
        <option v-for="item  in data" :value="item[label]">{{  item[text]  }}</option>
    </select>   
</template>