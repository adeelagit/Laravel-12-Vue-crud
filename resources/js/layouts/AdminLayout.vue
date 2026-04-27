<script setup lang="ts">
import AdminSidebarLayout from '@/layouts/admin/AdminSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AdminSidebarLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AdminSidebarLayout>
</template>
