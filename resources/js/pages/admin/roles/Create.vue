<script setup lang="ts">
import AppLayout from '@/layouts/admin/AdminSidebarLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Roles and Permission',
        href: '/admin/roles/create',
    },
];

interface Permission{
    id: number,
    name: string,
}

interface Props{
    permissions: Record<string, Permission[]>,
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    permissions: [] as number[],
});

const isGroupSelected = (group: Permission[]) => {
    return group.every(p => form.permissions.includes(p.id))
}

const toggleGroup = (group: Permission[], event: Event) => {
    const checked = (event.target as HTMLInputElement).checked

    if (checked) {
        // add all IDs (avoid duplicates)
        const ids = group.map(p => p.id)
        form.permissions = Array.from(new Set([...form.permissions, ...ids]))
    } else {
        // remove all group IDs
        const ids = group.map(p => p.id)
        form.permissions = form.permissions.filter(id => !ids.includes(id))
    }
}

const handleSubmit = () => {
    form.post(route('admin.store.roles'));
}
</script>

<template>
    <Head title="Create Roles and Permissions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form class="w-8/12 space-y-4" @submit.prevent="handleSubmit">
                <div class="space-y-2">
                    <Label for="user name">Role Name</Label>
                    <Input type="text" placeholder="Role Name" v-model="form.name"/>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div>
                    <!-- list available permissions for checkbox -->
                    <div class="space-y-4">
                        <div
                            v-for="(group, groupName) in props.permissions"
                            :key="groupName"
                            class="border rounded p-4"
                        >
                            <!-- Group Header -->
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold">{{ groupName }}</h3>

                                <!-- Select All Checkbox -->
                                <input
                                    type="checkbox"
                                    :checked="isGroupSelected(group)"
                                    @change="toggleGroup(group, $event)"
                                />
                            </div>

                            <!-- Permissions -->
                            <div class="grid grid-cols-2 gap-2">
                                <label
                                    v-for="perm in group"
                                    :key="perm.id"
                                    class="flex items-center space-x-2"
                                >
                                    <input
                                        type="checkbox"
                                        :value="perm.id"
                                        v-model="form.permissions"
                                    />
                                    <span>{{ perm.name }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <Button type="submit" :disabled="form.processing">Add a role</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
