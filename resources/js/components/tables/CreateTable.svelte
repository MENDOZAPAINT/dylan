<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import * as Dialog from '@/components/ui/dialog';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';

    let open = $state(false);
    let isActive = $state(true);
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger class={buttonVariants()}>Nueva orden</Dialog.Trigger>
    <Dialog.Content class="sm:max-w-xl">
        <Dialog.Header>
            <Dialog.Title>Nueva orden</Dialog.Title>
            <Dialog.Description>
                Completa los datos para registrar una orden.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action="/orders"
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
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for="number">Numero</Label>
                        <Input
                            id="number"
                            name="number"
                            type="number"
                            min="1"
                            required
                            placeholder="1"
                        />
                        <InputError message={errors.number} />
                    </div>

                    <div class="grid gap-2">
                        <Label for="zone">Zona</Label>
                        <Input
                            id="zone"
                            name="zone"
                            autocomplete="off"
                            placeholder="Terraza"
                        />
                        <InputError message={errors.zone} />
                    </div>

                    <div class="grid gap-2">
                        <Label for="capacity">Capacidad</Label>
                        <Input
                            id="capacity"
                            name="capacity"
                            type="number"
                            min="1"
                            value="4"
                        />
                        <InputError message={errors.capacity} />
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="status">Estado</Label>
                        <select
                            id="status"
                            name="status"
                            class="border-input bg-background ring-offset-background focus-visible:ring-ring h-8 w-full rounded-md border px-3 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                        >
                            <option value="free">Libre</option>
                            <option value="occupied">Ocupada</option>
                        </select>
                        <InputError message={errors.status} />
                    </div>

                    <div class="grid gap-2">
                        <Label for="mozo_id">Mozo ID</Label>
                        <Input
                            id="mozo_id"
                            name="mozo_id"
                            type="number"
                            min="1"
                            placeholder="Opcional"
                        />
                        <InputError message={errors.mozo_id} />
                    </div>
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
                    />
                    Activa
                </label>
                <InputError message={errors.is_active} />

                <div class="flex justify-end">
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Guardando...' : 'Guardar mesa'}
                    </Button>
                </div>
            {/snippet}
        </Form>
    </Dialog.Content>
</Dialog.Root>
