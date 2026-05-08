<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import * as Dialog from '@/components/ui/dialog';

    type Category = {
        id: number;
        name: string;
    };

    let { category }: { category: Category } = $props();
    let open = $state(false);
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger
        class={buttonVariants({ variant: 'destructive', size: 'sm' })}
    >
        Eliminar
    </Dialog.Trigger>
    <Dialog.Content>
        <Dialog.Header>
            <Dialog.Title>Eliminar categoria</Dialog.Title>
            <Dialog.Description>
                Esta accion eliminara "{category.name}" permanentemente.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action={`/categories/${category.id}`}
            method="delete"
            options={{ preserveScroll: true }}
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
