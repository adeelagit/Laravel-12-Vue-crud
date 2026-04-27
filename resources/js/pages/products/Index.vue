<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { Plus, Rocket } from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import Button from '@/components/ui/button/Button.vue';
import Pagination from '@/components/Pagination.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
];
const page = usePage();

interface Product{
    id: number,
    name: string,
    price: number,
    description: string,
}

interface Props{
    products: Product[],
}
const props = defineProps<Props>();

const handleDelete = (id:number) => {
    if(confirm('Do you want to delete this product?')){
        router.delete(route('products.destroy', {id}));
    }
}

</script>

<template>
    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 m-2">
            <div v-if="page.props.flash?.message" class="mb-4">
                <Alert class="bg-blue-200">
                    <Rocket class="h-4 w-4"/>
                    <AlertTitle>Notification!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash?.message }}
                    </AlertDescription>
                </Alert>
            </div>
            
            <div>
                <Link :href="route('products.create')" class="px-4 py-2 bg-slate-600 text-white rounded-md">
                    <Plus class="inline-block space-x-2" />
                    Create Product
                </Link>
            </div>

            <div>
                <Table class="mt-4">
                    <TableCaption>A list of your recent products.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">Id</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="product in props.products.data" :key="product.id">
                            <TableCell class="font-medium">{{ product.id }}</TableCell>
                            <TableCell class="font-medium">{{ product.name }}</TableCell>
                            <TableCell>{{ product.price }}</TableCell>
                            <TableCell>{{ product.description }}</TableCell>
                            <TableCell class="text-center space-x-2">
                                <Link :href="route('products.edit', [product.id])"> 
                                    <Button class="bg-slate-600 text-white">Edit</Button>
                                </Link>
                                <Button class="bg-red-600 text-white" @click="handleDelete(product.id)">Delete</Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <Pagination :links="props.products.links"/>
            </div>
        </div>
    </AppLayout>
</template>
