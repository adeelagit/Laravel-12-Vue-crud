<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('admin.login'));
};
</script>

<template>
    <AuthLayout
        title="Admin login"
        description="Enter your email and password to continue"
    >
        <Head title="Admin login" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="admin@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <Button type="submit" class="w-full" :disabled="form.processing">
                    {{ form.processing ? 'Signing in...' : 'Sign in' }}
                </Button>
            </div>
        </form>
    </AuthLayout>
</template>