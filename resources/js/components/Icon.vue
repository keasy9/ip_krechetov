<script setup lang="ts">
    import {defineAsyncComponent} from "vue";

    const props = defineProps<{
        file: string,
    }>();

    const icon = defineAsyncComponent(() => import(`../../symbols/${props.file}.svg`));
</script>

<template>
    <component :is="icon"></component>
</template>
