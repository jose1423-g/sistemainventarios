<script setup>

import { ref } from 'vue';

defineProps({
    id: {
        type: String,
    },
    Files: {
        type: String,
        required: false
    },
    typefiles: {
        type: Array,
        required: false,
    },
})

const input = ref(null);
const emit = defineEmits(['update:modelValue', 'reset']);
const filename = ref('Sube tu archivo...');

function  GetNameFile () {
    const file = input.value.files[0];
    filename.value = file ? file.name : 'Sube tu archivo';
    
    emit('update:modelValue', file);
}

function  Clearfilename () {    
    filename.value = 'Sube tu archivo...';
}

defineExpose({ Clearfilename })

</script>

<template>
    
    <div class="flex items-center ">
        <label :for="id" class="flex-none px-3 py-2 text-sm font-medium text-gray-200 bg-gray-900 rounded-l cursor-pointer hover:bg-gray-500">
            choose file
        </label>
        <input ref="input" type="file" :accept="typefiles" :id="id" class="hidden" @change="GetNameFile">
        <span class="w-full px-4 py-2 text-sm text-gray-600 truncate bg-gray-100 border-gray-300 rounded-r ">
            {{ filename }}
        </span>
    </div>
    
</template>