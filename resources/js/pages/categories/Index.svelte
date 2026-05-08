<script module lang="ts">
    import categories from '@/routes/categories';

    export const layout = {
        breadcrumbs: [
            {
                title: 'Categorias',
                href: categories.index(),
            },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import CreateCategory from '@/components/categories/CreateCategory.svelte';
    import DeleteCategory from '@/components/categories/DeleteCategory.svelte';
    import EditCategory from '@/components/categories/EditCategory.svelte';
    import * as Table from '@/components/ui/table';
    import { Checkbox } from '@/components/ui/checkbox';
    import { Badge } from '@/components/ui/badge';

    type Category = {
        id: number;
        name: string;
        description: string | null;
        order: number;
        is_active: boolean;
    };

    let { categories: categoryList = [] }: { categories?: Category[] } =
        $props();
</script>

<AppHead title="Categorias" />

<div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold tracking-tight">Categorias</h1>
        <CreateCategory />
    </div>

    <div class="overflow-hidden rounded-lg border">
        <Table.Root>
            <Table.Caption
                >Listado de categorias registradas. <Checkbox id="terms" />
            </Table.Caption>
            <Table.Header>
                <Table.Row>
                    <Table.Head class="w-[100px]">Nombre</Table.Head>
                    <Table.Head>Descripcion</Table.Head>
                    <Table.Head>Orden</Table.Head>
                    <Table.Head class="text-end">Estado</Table.Head>
                    <Table.Head class="text-end">Acciones</Table.Head>
                </Table.Row>
            </Table.Header>
            <Table.Body>
                {#each categoryList as category (category.id)}
                    <Table.Row>
                        <Table.Cell class="font-medium"
                            >{category.name}</Table.Cell
                        >
                        <Table.Cell>{category.description}</Table.Cell>
                        <Table.Cell>{category.order}</Table.Cell>
                        <Table.Cell class="text-end">
                            <Badge
                                variant={category.is_active
                                    ? 'success'
                                    : 'destructive'}
                            >
                                {category.is_active ? 'Activa' : 'Inactiva'}
                            </Badge>
                        </Table.Cell>
                        <Table.Cell>
                            <div class="flex justify-end gap-2">
                                <EditCategory {category} />
                                <DeleteCategory {category} />
                            </div>
                        </Table.Cell>
                    </Table.Row>
                {:else}
                    <Table.Row>
                        <Table.Cell
                            colspan={5}
                            class="py-6 text-center text-muted-foreground"
                        >
                            No hay categorias registradas.
                        </Table.Cell>
                    </Table.Row>
                {/each}
            </Table.Body>
        </Table.Root>
    </div>
</div>
