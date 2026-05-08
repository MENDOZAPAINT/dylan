<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import * as Dialog from '@/components/ui/dialog';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';

    type Category = {
        id: number;
        name: string;
    };

    let { categories = [] }: { categories?: Category[] } = $props();
    let open = $state(false);
    let isHighlighted = $state(false);
    let isAvailable = $state(true);
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger class={buttonVariants()}>Nuevo plato</Dialog.Trigger>
    <Dialog.Content class="sm:max-w-xl">
        <Dialog.Header>
            <Dialog.Title>Nuevo plato</Dialog.Title>
            <Dialog.Description>
                Completa los datos para registrar un plato.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action="/dishes"
            method="post"
            class="grid gap-4"
            options={{ preserveScroll: true }}
            resetOnSuccess
            onSuccess={() => {
                open = false;
                isHighlighted = false;
                isAvailable = true;
            }}
        >
            {#snippet children({ errors, processing })}
                <div class="grid gap-2">
                    <Label for="category_id">Categoria</Label>
                    <select
                        id="category_id"
                        name="category_id"
                        required
                        class="border-input bg-background ring-offset-background focus-visible:ring-ring h-8 w-full rounded-md border px-3 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                    >
                        <option value="">Selecciona una categoria</option>
                        {#each categories as category (category.id)}
                            <option value={category.id}>{category.name}</option>
                        {/each}
                    </select>
                    <InputError message={errors.category_id} />
                </div>

                <div class="grid gap-2">
                    <Label for="name">Nombre</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        autocomplete="off"
                        placeholder="Ej. Lomo saltado"
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

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for="price">Precio</Label>
                        <Input
                            id="price"
                            name="price"
                            type="number"
                            min="0"
                            step="0.01"
                            required
                            placeholder="0.00"
                        />
                        <InputError message={errors.price} />
                    </div>

                    <div class="grid gap-2">
                        <Label for="preparation_time">Minutos</Label>
                        <Input
                            id="preparation_time"
                            name="preparation_time"
                            type="number"
                            min="0"
                            placeholder="15"
                        />
                        <InputError message={errors.preparation_time} />
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
                </div>

                <div class="grid gap-2">
                    <Label for="image">Imagen</Label>
                    <Input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/*"
                    />
                    <InputError message={errors.image} />
                </div>

                <div class="flex flex-wrap gap-4">
                    <label
                        for="is_highlighted"
                        class="flex items-center gap-2 text-sm font-medium"
                    >
                        <Checkbox
                            id="is_highlighted"
                            name="is_highlighted"
                            value="1"
                            bind:checked={isHighlighted}
                        />
                        Destacado
                    </label>

                    <label
                        for="is_available"
                        class="flex items-center gap-2 text-sm font-medium"
                    >
                        <Checkbox
                            id="is_available"
                            name="is_available"
                            value="1"
                            bind:checked={isAvailable}
                        />
                        Disponible
                    </label>
                </div>
                <InputError message={errors.is_highlighted} />
                <InputError message={errors.is_available} />

                <div class="flex justify-end">
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Guardando...' : 'Guardar plato'}
                    </Button>
                </div>
            {/snippet}
        </Form>
    </Dialog.Content>
</Dialog.Root>
