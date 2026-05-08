<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import * as Dialog from '@/components/ui/dialog';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Checkbox } from '../ui/checkbox';

    let open = $state(false);
    let isActive = $state(true);
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger class={buttonVariants()}>Nueva categoria</Dialog.Trigger>
    <Dialog.Content>
        <Dialog.Header>
            <Dialog.Title>Nueva categoria</Dialog.Title>
            <Dialog.Description>
                Completa los datos para registrar una categoria.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action="/categories"
            method="post"
            class="grid gap-4"
            options={{ preserveScroll: true }}
            resetOnSuccess
            onSuccess={() => {
                open = false;
                isActive = true;
            }}
        >
            {#snippet children({ errors, processing })}
                <div class="grid gap-2">
                    <Label for="name">Nombre</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        autocomplete="off"
                        placeholder="Ej. Entradas"
                    />
                    <InputError message={errors.name} />
                </div>

                <div class="grid gap-2">
                    <Label for="description">Descripcion</Label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring min-h-20 w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Descripcion opcional"
                    ></textarea>
                    <InputError message={errors.description} />
                </div>

                <div class="grid gap-2">
                    <Label for="order">Orden</Label>
                    <Input
                        id="order"
                        name="order"
                        type="number"
                        min="0"
                        value="0"
                    />
                    <InputError message={errors.order} />
                </div>

                <label
                    for="is_active"
                    class="flex items-center gap-2 text-sm font-medium"
                >
                    <Checkbox
                        id="is_active"
                        name="is_active"
                        value="1"
                        bind:checked={isActive}
                        class="size-4 rounded border"
                    />
                    Activa
                </label>
                <InputError message={errors.is_active} />

                <div class="flex justify-end">
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Guardando...' : 'Guardar categoria'}
                    </Button>
                </div>
            {/snippet}
        </Form>
    </Dialog.Content>
</Dialog.Root>
