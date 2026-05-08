<script lang="ts">
    import { onMount } from 'svelte';
    import { themeState } from '@/lib/theme.svelte';
    import type { Appearance } from '@/types';
    import {
        DropdownMenu,
        DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuTrigger,
    } from '@/components/ui/dropdown-menu';
    import { Button } from '@/components/ui/button';
    import {
        Monitor,
        Moon,
        Sun,
        Check,
        ChevronDown,
    } from 'lucide-svelte/icons';

    const { appearance, updateAppearance } = themeState();

    const tabs: { value: Appearance; icon: typeof Sun; label: string }[] = [
        { value: 'light', icon: Sun, label: 'Claro' },
        { value: 'dark', icon: Moon, label: 'Oscuro' },
        { value: 'system', icon: Monitor, label: 'Sistema' },
    ];

    const current = $derived(
        tabs.find((t) => t.value === appearance.value) ?? tabs[2],
    );

    // Inicializar tema solo en cliente
    onMount(async () => {
        const { initializeTheme } = await import('@/lib/theme.svelte');
        initializeTheme();
    });
</script>

<DropdownMenu>
    <DropdownMenuTrigger asChild>
        {#snippet children({ onclick, 'aria-expanded': ariaExpanded, 'data-state': dataState })}
            <Button
                variant="outline"
                class="gap-2"
                {onclick}
                aria-expanded={ariaExpanded}
                data-state={dataState}
            >
                <current.icon class="h-4 w-4" />
                <span class="hidden sm:inline">{current.label}</span>
                <ChevronDown class="h-4 w-4 opacity-50" />
            </Button>
        {/snippet}
    </DropdownMenuTrigger>
    <DropdownMenuContent align="start">
        {#each tabs as { value, icon: Icon, label } (value)}
            <DropdownMenuItem asChild>
                {#snippet children({ class: cls, onClick })}
                    <button
                        type="button"
                        class={cls}
                        onclick={() => {
                            onClick?.();
                            updateAppearance(value);
                        }}
                    >
                        <Icon class="h-4 w-4" />
                        {label}
                        {#if appearance.value === value}
                            <Check class="ml-auto h-4 w-4" />
                        {/if}
                    </button>
                {/snippet}
            </DropdownMenuItem>
        {/each}
    </DropdownMenuContent>
</DropdownMenu>