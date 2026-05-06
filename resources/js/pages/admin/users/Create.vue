<script setup lang="ts">
import AppLayout from '@/layouts/admin/AdminSidebarLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a user',
        href: '/admin/users/create',
    },
];

const form = useForm({
    name:'',
    email:'',
    password:'',
});

const handleSubmit = () => {
    form.post(route('admin.users.store'));
}
</script>

<template>
    <Head title="Create a User" />

    <AppLayout :breadcrumbs="breadcrumbs">
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
                    <Label for="user password">Password</Label>
                    <Input type="password" placeholder="Password" v-model="form.password"/>
                    <div class="text-sm text-red-600" v-if="form.errors.password">{{ form.errors.password }}</div>
                </div>
                <div class="space-y-2">
                    <Button type="submit" :disabled="form.processing">Add a User</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
