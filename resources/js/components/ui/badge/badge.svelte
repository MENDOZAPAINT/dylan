<script lang="ts" module>
	import { type VariantProps, tv } from "tailwind-variants";

	export const badgeVariants = tv({
		base: "h-5 gap-1 rounded-4xl border border-transparent px-2 py-0.5 text-xs font-medium transition-all has-data-[icon=inline-end]:pr-1.5 has-data-[icon=inline-start]:pl-1.5 [&>svg]:size-3! focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive group/badge inline-flex w-fit shrink-0 items-center justify-center overflow-hidden whitespace-nowrap transition-colors focus-visible:ring-[3px] [&>svg]:pointer-events-none",
		variants: {
variant: {
    default: "bg-primary text-primary-foreground [a]:hover:bg-primary/80",
    secondary: "bg-secondary text-secondary-foreground [a]:hover:bg-secondary/80",
    destructive: "bg-destructive/10 [a]:hover:bg-destructive/20 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40 text-destructive dark:bg-destructive/20",
    outline: "border-border text-foreground [a]:hover:bg-muted [a]:hover:text-muted-foreground",
    ghost: "hover:bg-muted hover:text-muted-foreground dark:hover:bg-muted/50",
    link: "text-primary underline-offset-4 hover:underline",

    // Verdes
    success: "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 dark:bg-emerald-500/20 [a]:hover:bg-emerald-500/20",
    green: "bg-green-500 text-white [a]:hover:bg-green-500/80",

    // Amarillos / Advertencia
    warning: "bg-amber-500/10 text-amber-600 dark:text-amber-400 dark:bg-amber-500/20 [a]:hover:bg-amber-500/20",
    yellow: "bg-yellow-500 text-white [a]:hover:bg-yellow-500/80",

    // Azules
    info: "bg-blue-500/10 text-blue-600 dark:text-blue-400 dark:bg-blue-500/20 [a]:hover:bg-blue-500/20",
    blue: "bg-blue-500 text-white [a]:hover:bg-blue-500/80",
    sky: "bg-sky-500/10 text-sky-600 dark:text-sky-400 dark:bg-sky-500/20 [a]:hover:bg-sky-500/20",
    indigo: "bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 dark:bg-indigo-500/20 [a]:hover:bg-indigo-500/20",

    // Morados
    purple: "bg-purple-500/10 text-purple-600 dark:text-purple-400 dark:bg-purple-500/20 [a]:hover:bg-purple-500/20",
    violet: "bg-violet-500/10 text-violet-600 dark:text-violet-400 dark:bg-violet-500/20 [a]:hover:bg-violet-500/20",
    fuchsia: "bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 dark:bg-fuchsia-500/20 [a]:hover:bg-fuchsia-500/20",

    // Rosas / Rojos
    pink: "bg-pink-500/10 text-pink-600 dark:text-pink-400 dark:bg-pink-500/20 [a]:hover:bg-pink-500/20",
    rose: "bg-rose-500/10 text-rose-600 dark:text-rose-400 dark:bg-rose-500/20 [a]:hover:bg-rose-500/20",

    // Neutros
    slate: "bg-slate-500/10 text-slate-600 dark:text-slate-400 dark:bg-slate-500/20 [a]:hover:bg-slate-500/20",
    zinc: "bg-zinc-500/10 text-zinc-600 dark:text-zinc-400 dark:bg-zinc-500/20 [a]:hover:bg-zinc-500/20",
    orange: "bg-orange-500/10 text-orange-600 dark:text-orange-400 dark:bg-orange-500/20 [a]:hover:bg-orange-500/20",
    teal: "bg-teal-500/10 text-teal-600 dark:text-teal-400 dark:bg-teal-500/20 [a]:hover:bg-teal-500/20",
    cyan: "bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 dark:bg-cyan-500/20 [a]:hover:bg-cyan-500/20",
},
		},
		defaultVariants: {
			variant: "default",
		},
	});

	export type BadgeVariant = VariantProps<typeof badgeVariants>["variant"];
</script>

<script lang="ts">
	import type { HTMLAnchorAttributes } from "svelte/elements";
	import { cn, type WithElementRef } from "@/lib/utils.js";

	let {
		ref = $bindable(null),
		href,
		class: className,
		variant = "default",
		children,
		...restProps
	}: WithElementRef<HTMLAnchorAttributes> & {
		variant?: BadgeVariant;
	} = $props();
</script>

<svelte:element
	this={href ? "a" : "span"}
	bind:this={ref}
	data-slot="badge"
	{href}
	class={cn(badgeVariants({ variant }), className)}
	{...restProps}
>
	{@render children?.()}
</svelte:element>
