<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button, buttonVariants } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import * as Dialog from '@/components/ui/dialog';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Edit } from 'lucide-svelte';

    type Category = {
        id: number;
        name: string;
    };

    type Dish = {
        id: number;
        category_id: number;
        name: string;
        description: string | null;
        price: string | number;
        image_url: string | null;
        is_highlighted: boolean;
        is_available: boolean;
        preparation_time: number | null;
        order: number;
    };

    let {
        dish,
        categories = [],
    }: {
        dish: Dish;
        categories?: Category[];
    } = $props();

    let open = $state(false);
    let isHighlighted = $state(false);
    let isAvailable = $state(true);

    $effect(() => {
        if (open) {
            isHighlighted = !!dish.is_highlighted;
            isAvailable = !!dish.is_available;
        }
    });
</script>

<Dialog.Root bind:open>
    <Dialog.Trigger class={buttonVariants({ variant: 'success', size: 'sm' })}>
        <Edit/>
    </Dialog.Trigger>
    <Dialog.Content class="sm:max-w-xl">
        <Dialog.Header>
            <Dialog.Title>Editar plato</Dialog.Title>
            <Dialog.Description>
                Actualiza los datos del plato seleccionado.
            </Dialog.Description>
        </Dialog.Header>

        <Form
            action={`/dishes/${dish.id}`}
            method="post"
            class="grid gap-4"
            options={{ preserveScroll: true }}
            onSuccess={() => (open = false)}
        >
            {#snippet children({ errors, processing })}
                <input type="hidden" name="_method" value="PUT" />

                <div class="grid gap-2">
                    <Label for={`category-${dish.id}`}>Categoria</Label>
                    <select
                        id={`category-${dish.id}`}
                        name="category_id"
                        required
                        class="border-input bg-background ring-offset-background focus-visible:ring-ring h-8 w-full rounded-md border px-3 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                    >
                        {#each categories as category}
                            <option
                                value={category.id}
                                selected={category.id === dish.category_id}
                            >
                                {category.name}
                            </option>
                        {/each}
                    </select>
                    <InputError message={errors.category_id} />
                </div>

                <div class="grid gap-2">
                    <Label for={`name-${dish.id}`}>Nombre</Label>
                    <Input
                        id={`name-${dish.id}`}
                        name="name"
                        required
                        autocomplete="off"
                        value={dish.name}
                    />
                    <InputError message={errors.name} />
                </div>

                <div class="grid gap-2">
                    <Label for={`description-${dish.id}`}>Descripcion</Label>
                    <textarea
                        id={`description-${dish.id}`}
                        name="description"
                        rows="3"
                        class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring min-h-20 w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >{dish.description ?? ''}</textarea
                    >
                    <InputError message={errors.description} />
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for={`price-${dish.id}`}>Precio</Label>
                        <Input
                            id={`price-${dish.id}`}
                            name="price"
                            type="number"
                            min="0"
                            step="0.01"
                            required
                            value={dish.price}
                        />
                        <InputError message={errors.price} />
                    </div>

                    <div class="grid gap-2">
                        <Label for={`preparation-${dish.id}`}>Minutos</Label>
                        <Input
                            id={`preparation-${dish.id}`}
                            name="preparation_time"
                            type="number"
                            min="0"
                            value={dish.preparation_time ?? ''}
                        />
                        <InputError message={errors.preparation_time} />
                    </div>

                    <div class="grid gap-2">
                        <Label for={`order-${dish.id}`}>Orden</Label>
                        <Input
                            id={`order-${dish.id}`}
                            name="order"
                            type="number"
                            min="0"
                            value={dish.order}
                        />
                        <InputError message={errors.order} />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for={`image-${dish.id}`}>Imagen</Label>
                    {#if dish.image_url}
                        <img
                            src={dish.image_url}
                            alt={dish.name}
                            class="h-20 w-20 rounded-md object-cover"
                        />
                    {/if}
                    <Input
                        id={`image-${dish.id}`}
                        name="image"
                        type="file"
                        accept="image/*"
                    />
                    <InputError message={errors.image} />
                </div>

                <div class="flex flex-wrap gap-4">
                    <label
                        for={`highlighted-${dish.id}`}
                        class="flex items-center gap-2 text-sm font-medium"
                    >
                        <Checkbox
                            id={`highlighted-${dish.id}`}
                            name="is_highlighted"
                            value="1"
                            bind:checked={isHighlighted}
                        />
                        Destacado
                    </label>

                    <label
                        for={`available-${dish.id}`}
                        class="flex items-center gap-2 text-sm font-medium"
                    >
                        <Checkbox
                            id={`available-${dish.id}`}
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
                        {processing ? 'Guardando...' : 'Guardar cambios'}
                    </Button>
                </div>
            {/snippet}
        </Form>
    </Dialog.Content>
</Dialog.Root>
