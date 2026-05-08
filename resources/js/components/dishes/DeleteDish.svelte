<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import * as Dialog from '@/components/ui/dialog';
    import { DeleteIcon } from 'lucide-svelte';

    type Dish = {
        id: number;
        name: string;
    };

    let { dish }: { dish: Dish } = $props();
    let open = $state(false);
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger
        class={buttonVariants({ variant: 'destructive', size: 'sm' })}
    >
        <DeleteIcon/>
    </Dialog.Trigger>
    <Dialog.Content>
        <Dialog.Header>
            <Dialog.Title>Eliminar plato</Dialog.Title>
            <Dialog.Description>
                Esta accion eliminara "{dish.name}" permanentemente.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action={`/dishes/${dish.id}`}
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
