<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import * as Dialog from '@/components/ui/dialog';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Checkbox } from '@/components/ui/checkbox/index.js';

    type Category = {
        id: number;
        name: string;
        description: string | null;
        order: number;
        is_active: boolean;
    };

    let { category }: { category: Category } = $props();
    let open = $state(false);
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger class={buttonVariants({ variant: 'success', size: 'sm' })}>
        Editar
    </Dialog.Trigger>
    <Dialog.Content>
        <Dialog.Header>
            <Dialog.Title>Editar categoria</Dialog.Title>
            <Dialog.Description>
                Actualiza los datos de la categoria seleccionada.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action={`/categories/${category.id}`}
            method="put"
            class="grid gap-4"
            options={{ preserveScroll: true }}
            onSuccess={() => (open = false)}
        >
            {#snippet children({ errors, processing })}
                <div class="grid gap-2">
                    <Label for={`name-${category.id}`}>Nombre</Label>
                    <Input
                        id={`name-${category.id}`}
                        name="name"
                        required
                        autocomplete="off"
                        value={category.name}
                    />
                    <InputError message={errors.name} />
                </div>

                <div class="grid gap-2">
                    <Label for={`description-${category.id}`}>Descripcion</Label
                    >
                    <textarea
                        id={`description-${category.id}`}
                        name="description"
                        rows="3"
                        class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring min-h-20 w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >{category.description ?? ''}</textarea
                    >
                    <InputError message={errors.description} />
                </div>

                <div class="grid gap-2">
                    <Label for={`order-${category.id}`}>Orden</Label>
                    <Input
                        id={`order-${category.id}`}
                        name="order"
                        type="number"
                        min="0"
                        value={category.order}
                    />
                    <InputError message={errors.order} />
                </div>

                <label
                    for={`is-active-${category.id}`}
                    class="flex items-center gap-2 text-sm font-medium"
                >
                    <Checkbox
                        id={`is-active-${category.id}`}
                        name="is_active"
                        value="1"
                        checked={category.is_active}
                    />
                    Activa
                </label>
                <InputError message={errors.is_active} />

                <div class="flex justify-end">
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Guardando...' : 'Guardar cambios'}
                    </Button>
                </div>
            {/snippet}
        </Form>
    </Dialog.Content>
</Dialog.Root>
