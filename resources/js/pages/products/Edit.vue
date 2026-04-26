<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';

interface Product{
    id: number,
    name: string,
    price: number,
    description: string,
}

const props = defineProps<{
    product: Product,
}>();

const form = useForm({
    name: props.product?.name,
    price: props.product?.price,
    description: props.product?.description,
});

const handleSubmit = () => {
    form.put(route('products.update', {product: props.product.id}));
}
</script>

<template>
    <Head title="Edit a Product" />

    <AppLayout :breadcrumbs="[{ title: 'Edit a product', href: `/products/${props.product.id}/edit` }]">
        <div class="p-4">
            <form class="w-8/12 space-y-4" @submit.prevent="handleSubmit">
                <div class="space-y-2">
                    <Label for="product name">Name</Label>
                    <Input type="text" placeholder="Name" v-model="form.name"/>
                    <div class="text-sm text-red-600" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-2">
                    <Label for=" product price">Price</Label>
                    <Input type="number" placeholder="Price" v-model="form.price"/>
                    <div class="text-sm text-red-600" v-if="form.errors.price">{{ form.errors.price }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="product description">Description</Label>
                    <Textarea placeholder="Description" v-model="form.description"/>
                    <div class="text-sm text-red-600" v-if="form.errors.description">{{ form.errors.description }}</div>
                </div>
                <div class="space-y-2">
                    <Button type="submit" :disabled="form.processing">Edit a Product</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
