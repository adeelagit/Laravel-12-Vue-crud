<script setup lang="ts">
import AppLayout from '@/layouts/admin/AdminSidebarLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';

interface User{
    id: number,
    name: string,
    email: string,
}

interface Permissions{
    can_edit: boolean,
    can_delete: boolean,
}

const props = defineProps<{
    user: User,
    permissions?: Permissions,
}>();

const form = useForm({
    name: props.user?.name,
    email: props.user?.email,
    password: '',
});

const handleSubmit = () => {
    form.put(route('admin.users.update', {user: props.user.id}));
}

const handleDelete = () => {
    if(confirm('Do you want to delete this user?')){
        router.delete(route('admin.users.destroy', {id: props.user.id}));
    }
}
</script>

<template>
    <Head title="Edit a User" />

    <AppLayout :breadcrumbs="[{ title: 'Edit a user', href: `/admin/users/${props.user.id}/edit` }]">
        <div class="p-4">
            <form class="w-8/12 space-y-4" @submit.prevent="handleSubmit">
                <div class="space-y-2">
                    <Label for="user name">Name</Label>
                    <Input type="text" placeholder="Name" v-model="form.name"/>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="user email">Email</Label>
                    <Input type="email" placeholder="Email" v-model="form.email"/>
                    <div class="text-sm text-red-600" v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="user password">Password (leave empty to keep current password)</Label>
                    <Input type="password" placeholder="Password" v-model="form.password"/>
                    <div class="text-sm text-red-600" v-if="form.errors.password">{{ form.errors.password }}</div>
                </div>
                <div class="space-y-2">
                    <!-- Show Edit button only if user has permission -->
                    <Button v-if="props.permissions?.can_edit" type="submit" :disabled="form.processing">Edit a User</Button>
                    <!-- Show Delete button only if user has permission -->
                    <Button v-if="props.permissions?.can_delete" class="bg-red-600 text-white ml-2" @click="handleDelete">Delete User</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
