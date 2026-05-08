<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import { Edit } from 'lucide-svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import * as Dialog from '@/components/ui/dialog';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';

    type RestaurantTable = {
        id: number;
        number: number;
        zone: string | null;
        capacity: number;
        status: 'free' | 'occupied';
        mozo_id: number | null;
        is_active: boolean;
    };

    let { table }: { table: RestaurantTable } = $props();

    let open = $state(false);
    let isActive = $state(true);

    $effect(() => {
        if (open) {
            isActive = !!table.is_active;
        }
    });
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger class={buttonVariants({ variant: 'success', size: 'sm' })}>
        <Edit />
    </Dialog.Trigger>
    <Dialog.Content class="sm:max-w-xl">
        <Dialog.Header>
            <Dialog.Title>Editar mesa</Dialog.Title>
            <Dialog.Description>
                Actualiza los datos de la mesa seleccionada.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action={`/tables/${table.id}`}
            method="post"
            class="grid gap-4"
            options={{ preserveScroll: true }}
            onSuccess={() => (open = false)}
        >
            {#snippet children({ errors, processing })}
                <input type="hidden" name="_method" value="PUT" />

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for={`number-${table.id}`}>Numero</Label>
                        <Input
                            id={`number-${table.id}`}
                            name="number"
                            type="number"
                            min="1"
                            required
                            value={table.number}
                        />
                        <InputError message={errors.number} />
                    </div>

                    <div class="grid gap-2">
                        <Label for={`zone-${table.id}`}>Zona</Label>
                        <Input
                            id={`zone-${table.id}`}
                            name="zone"
                            autocomplete="off"
                            value={table.zone ?? ''}
                        />
                        <InputError message={errors.zone} />
                    </div>

                    <div class="grid gap-2">
                        <Label for={`capacity-${table.id}`}>Capacidad</Label>
                        <Input
                            id={`capacity-${table.id}`}
                            name="capacity"
                            type="number"
                            min="1"
                            value={table.capacity}
                        />
                        <InputError message={errors.capacity} />
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for={`status-${table.id}`}>Estado</Label>
                        <select
                            id={`status-${table.id}`}
                            name="status"
                            class="border-input bg-background ring-offset-background focus-visible:ring-ring h-8 w-full rounded-md border px-3 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                        >
                            <option
                                value="free"
                                selected={table.status === 'free'}
                            >
                                Libre
                            </option>
                            <option
                                value="occupied"
                                selected={table.status === 'occupied'}
                            >
                                Ocupada
                            </option>
                        </select>
                        <InputError message={errors.status} />
                    </div>

                    <div class="grid gap-2">
                        <Label for={`mozo-${table.id}`}>Mozo ID</Label>
                        <Input
                            id={`mozo-${table.id}`}
                            name="mozo_id"
                            type="number"
                            min="1"
                            value={table.mozo_id ?? ''}
                            placeholder="Opcional"
                        />
                        <InputError message={errors.mozo_id} />
                    </div>
                </div>

                <label
                    for={`active-${table.id}`}
                    class="flex items-center gap-2 text-sm font-medium"
                >
                    <Checkbox
                        id={`active-${table.id}`}
                        name="is_active"
                        value="1"
                        bind:checked={isActive}
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
