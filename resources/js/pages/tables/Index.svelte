<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            {
                title: 'Mesas',
                href: '/tables',
            },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import CreateTable from '@/components/tables/CreateTable.svelte';
    import DeleteTable from '@/components/tables/DeleteTable.svelte';
    import EditTable from '@/components/tables/EditTable.svelte';
    import { Badge } from '@/components/ui/badge';
    import * as Table from '@/components/ui/table';

    type RestaurantTable = {
        id: number;
        number: number;
        zone: string | null;
        capacity: number;
        status: 'free' | 'occupied';
        mozo_id: number | null;
        is_active: boolean;
        mozo?: {
            id: number;
            name: string;
        } | null;
    };

    let {
        tables: tableList = [],
    }: {
        tables?: RestaurantTable[];
    } = $props();
</script>

<AppHead title="Mesas" />

<div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold tracking-tight">Mesas</h1>
        <CreateTable />
    </div>

    <div class="overflow-hidden rounded-lg border">
        <Table.Root>
            <Table.Caption>Listado de mesas registradas.</Table.Caption>
            <Table.Header>
                <Table.Row>
                    <Table.Head>Numero</Table.Head>
                    <Table.Head>Zona</Table.Head>
                    <Table.Head>Capacidad</Table.Head>
                    <Table.Head>Mozo</Table.Head>
                    <Table.Head>Estado</Table.Head>
                    <Table.Head>Activa</Table.Head>
                    <Table.Head class="text-end">Acciones</Table.Head>
                </Table.Row>
            </Table.Header>
            <Table.Body>
                {#each tableList as table (table.id)}
                    <Table.Row>
                        <Table.Cell>
                            <div class="grid gap-1">
                                <span class="font-medium">{table.number}</span>
                                <span
                                    class="max-w-sm text-muted-foreground text-sm"
                                >
                                    {table.zone ?? 'Sin zona'}
                                </span>
                            </div>
                        </Table.Cell>
                        <Table.Cell>{table.zone ?? '-'}</Table.Cell>
                        <Table.Cell>{table.capacity} personas</Table.Cell>
                        <Table.Cell>
                            {table.mozo?.name ??
                                (table.mozo_id ? `#${table.mozo_id}` : '-')}
                        </Table.Cell>
                        <Table.Cell>
                            <div class="flex flex-wrap gap-2">
                                <Badge
                                    variant={table.status === 'free'
                                        ? 'success'
                                        : 'outline'}
                                >
                                    {table.status === 'free'
                                        ? 'Libre'
                                        : 'Ocupada'}
                                </Badge>
                            </div>
                        </Table.Cell>
                        <Table.Cell>
                            <Badge
                                variant={table.is_active
                                    ? 'success'
                                    : 'outline'}
                            >
                                {table.is_active ? 'Si' : 'No'}
                            </Badge>
                        </Table.Cell>
                        <Table.Cell>
                            <div class="flex justify-end gap-2">
                                <EditTable {table} />
                                <DeleteTable {table} />
                            </div>
                        </Table.Cell>
                    </Table.Row>
                {:else}
                    <Table.Row>
                        <Table.Cell
                            colspan={7}
                            class="py-6 text-center text-muted-foreground"
                        >
                            No hay mesas registradas.
                        </Table.Cell>
                    </Table.Row>
                {/each}
            </Table.Body>
        </Table.Root>
    </div>
</div>
