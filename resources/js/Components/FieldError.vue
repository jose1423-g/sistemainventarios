<script setup>
    defineProps({        
        message: {
            type: [Array, String],
        },
        textcolor: {
            type: String,
            default: 'text-red-600'
        }
    });
</script>
<template>

    <div v-if="message">
        <p class="text-sm" :class="textcolor">
            {{ Array.isArray(message) ? message[0] : message }}
        </p>
    </div>
    
</template>