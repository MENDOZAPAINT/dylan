<script module lang="ts">
    import dishes from '@/routes/dishes';

    export const layout = {
        breadcrumbs: [
            {
                title: 'Platos',
                href: dishes.index(),
            },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import CreateDish from '@/components/dishes/CreateDish.svelte';
    import DeleteDish from '@/components/dishes/DeleteDish.svelte';
    import EditDish from '@/components/dishes/EditDish.svelte';
    import { Badge } from '@/components/ui/badge';
    import * as Table from '@/components/ui/table';

    type Category = {
        id: number;
        name: string;
    };

    type Dish = {
        id: number;
        category_id: number;
        category: Category | null;
        name: string;
        description: string | null;
        price: string | number;
        image_url: string | null;
        is_highlighted: boolean;
        is_available: boolean;
        preparation_time: number | null;
        order: number;
    };

    let {
        dishes: dishList = [],
        categories = [],
    }: {
        dishes?: Dish[];
        categories?: Category[];
    } = $props();

    const formatPrice = (price: string | number) =>
        Number(price).toLocaleString('es-PE', {
            style: 'currency',
            currency: 'PEN',
        });
</script>

<AppHead title="Platos" />

<div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold tracking-tight">Platos</h1>
        <CreateDish {categories} />
    </div>

    <div class="overflow-hidden rounded-lg border">
        <Table.Root>
            <Table.Caption>Listado de platos registrados.</Table.Caption>
            <Table.Header>
                <Table.Row>
                    <Table.Head>Plato</Table.Head>
                    <Table.Head>Categoria</Table.Head>
                    <Table.Head>Precio</Table.Head>
                    <Table.Head>Tiempo</Table.Head>
                    <Table.Head>Estado</Table.Head>
                    <Table.Head>Imagen</Table.Head>
                    <Table.Head class="text-end">Acciones</Table.Head>
                </Table.Row>
            </Table.Header>
            <Table.Body>
                {#each dishList as dish (dish.id)}
                    <Table.Row>
                        <Table.Cell>
                            <div class="grid gap-1">
                                <span class="font-medium">{dish.name}</span>
                                <span
                                    class="max-w-sm text-muted-foreground text-sm"
                                >
                                    {dish.description ?? 'Sin descripcion'}
                                </span>
                            </div>
                        </Table.Cell>
                        <Table.Cell>{dish.category?.name ?? '-'}</Table.Cell>
                        <Table.Cell>{formatPrice(dish.price)}</Table.Cell>
                        <Table.Cell>
                            {dish.preparation_time
                                ? `${dish.preparation_time} min`
                                : '-'}
                        </Table.Cell>
                        <Table.Cell>
                            <div class="flex flex-wrap gap-2">
                                <Badge
                                    variant={dish.is_available
                                        ? 'success'
                                        : 'outline'}
                                >
                                    {dish.is_available
                                        ? 'Disponible'
                                        : 'No disponible'}
                                </Badge>
                                {#if dish.is_highlighted}
                                    <Badge variant="warning">Destacado</Badge>
                                {/if}
                            </div>
                        </Table.Cell>
                        <Table.Cell>
                            {#if dish.image_url}
                                <img
                                    src={dish.image_url}
                                    alt={dish.name}
                                    class="h-12 w-12 rounded-md object-cover"
                                />
                            {:else}
                                <Badge variant="outline">Sin imagen</Badge>
                            {/if}
                        </Table.Cell>
                        <Table.Cell>
                            <div class="flex justify-end gap-2">
                                <EditDish {dish} {categories} />
                                <DeleteDish {dish} />
                            </div>
                        </Table.Cell>
                    </Table.Row>
                {:else}
                    <Table.Row>
                        <Table.Cell
                            colspan={7}
                            class="py-6 text-center text-muted-foreground"
                        >
                            No hay platos registrados.
                        </Table.Cell>
                    </Table.Row>
                {/each}
            </Table.Body>
        </Table.Root>
    </div>
</div>
