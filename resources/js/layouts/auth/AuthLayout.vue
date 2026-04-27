<script setup lang="ts">
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';

interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <AuthSplitLayout :title="title" :description="description">
        <slot />
    </AuthSplitLayout>
</template>
