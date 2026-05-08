<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import { Trash2 } from 'lucide-svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import * as Dialog from '@/components/ui/dialog';

    type RestaurantTable = {
        id: number;
        number: number;
    };

    let { table }: { table: RestaurantTable } = $props();
    let open = $state(false);
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger
        class={buttonVariants({ variant: 'destructive', size: 'sm' })}
    >
        <Trash2 />
    </Dialog.Trigger>
    <Dialog.Content>
        <Dialog.Header>
            <Dialog.Title>Eliminar mesa</Dialog.Title>
            <Dialog.Description>
                Esta accion eliminara la mesa {table.number} permanentemente.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action={`/tables/${table.id}`}
            method="delete"
            options={{ preserveScroll: true }}
            onSuccess={() => (open = false)}
        >
            {#snippet children({ processing })}
                <div class="flex justify-end gap-2">
                    <Dialog.Close
                        class={buttonVariants({ variant: 'outline' })}
                    >
                        Cancelar
                    </Dialog.Close>
                    <Button
                        type="submit"
                        variant="destructive"
                        disabled={processing}
                    >
                        {processing ? 'Eliminando...' : 'Eliminar'}
                    </Button>
                </div>
            {/snippet}
        </Form>
    </Dialog.Content>
</Dialog.Root>
