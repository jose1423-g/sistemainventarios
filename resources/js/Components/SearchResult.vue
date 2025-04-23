<script setup>

import { defineEmits } from 'vue';

defineProps({
    id: {
        type: String,
        required: true,
    },
    data:{
        type: Array,
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
    },
})

const emit = defineEmits(['select']); // Define el evento

const handleSelect = (id, text, item) => {
    emit('select', id, text, item); // Emitir el valor seleccionado
};

</script>
<template>

    <ul :id="id" v-if="data.length" class="absolute z-30 w-full py-1 list-none bg-white border rounded-sm shadow-sm max-h-[15rem] overflow-y-auto mt-[.1rem]">
        <li class="px-2 py-1 cursor-pointer hover:bg-gray-300" v-for="item in data" @click="handleSelect(item[label], item[text], item)">{{ item[text] }}</li>
    </ul>
    
</template>