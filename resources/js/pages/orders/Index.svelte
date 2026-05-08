<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            {
                title: 'Ordenes',
                href: '/orders',
            },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import DeleteOrder from '@/components/orders/DeleteOrder.svelte';
    import EditOrder from '@/components/orders/EditOrder.svelte';
    import CreateTable from '@/components/tables/CreateTable.svelte';
    import DeleteTable from '@/components/tables/DeleteTable.svelte';
    import EditTable from '@/components/tables/EditTable.svelte';
    import { Badge } from '@/components/ui/badge';
    import * as Table from '@/components/ui/table';

    type Order = {
        id: number;
        table_id: number | null;
        mozo_id: number | null;
        total: number;
        status: 'pending' | 'in_kitchen' | 'ready' | 'delivered' | 'cancelled';
        general_notes: string | null;
    };

    let {
        orders: orderList = [],
    }: {
        orders?: Order[];
    } = $props();
</script>

<AppHead title="Ordenes" />

<div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold tracking-tight">Ordenes</h1>
        <CreateTable />
    </div>

    <div class="overflow-hidden rounded-lg border">
        <Table.Root>
            <Table.Caption>Listado de ordenes registradas.</Table.Caption>
            <Table.Header>
                <Table.Row>
                    <Table.Head>ID</Table.Head>
                    <Table.Head>Mesa</Table.Head>
                    <Table.Head>Mozo</Table.Head>
                    <Table.Head>Total</Table.Head>
                    <Table.Head>Estado</Table.Head>
                    <Table.Head>Notas</Table.Head>
                    <Table.Head class="text-end">Acciones</Table.Head>
                </Table.Row>
            </Table.Header>

            <Table.Body>
                {#each orderList as order (order.id)}
                    <Table.Row>
                        <!-- ID -->
                        <Table.Cell>
                            <span class="font-medium">#{order.id}</span>
                        </Table.Cell>

                        <!-- Mesa -->
                        <Table.Cell>
                            {order.table_id ?? '-'}
                        </Table.Cell>

                        <!-- Mozo -->
                        <Table.Cell>
                            {order.mozo_id ?? '-'}
                        </Table.Cell>

                        <!-- Total -->
                        <Table.Cell>
                            S/ {order.total.toFixed(2)}
                        </Table.Cell>

                        <!-- Estado -->
                        <Table.Cell>
                            <Badge
                                variant={order.status === 'delivered'
                                    ? 'success'
                                    : order.status === 'cancelled'
                                      ? 'destructive'
                                      : 'outline'}
                            >
                                {order.status}
                            </Badge>
                        </Table.Cell>

                        <!-- Notas -->
                        <Table.Cell>
                            {order.general_notes ?? '-'}
                        </Table.Cell>

                        <!-- Acciones -->
                        <Table.Cell>
                            <div class="flex justify-end gap-2">
                                <!-- <EditOrder {order} /> -->
                                <!-- <DeleteOrder {order} /> -->
                            </div>
                        </Table.Cell>
                    </Table.Row>
                {:else}
                    <Table.Row>
                        <Table.Cell
                            colspan={7}
                            class="py-6 text-center text-muted-foreground"
                        >
                            No hay órdenes registradas.
                        </Table.Cell>
                    </Table.Row>
                {/each}
            </Table.Body>
        </Table.Root>
    </div>
</div>
