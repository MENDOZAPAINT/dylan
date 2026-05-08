import type { LinkComponentBaseProps } from '@inertiajs/core';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export type WithElementRef<T, E = HTMLElement> = T & {
    ref?: E | null;
};

export type WithoutChild<T> = Omit<T, 'child'>;

export type WithoutChildren<T> = Omit<T, 'children'>;

export type WithoutChildrenOrChild<T> = Omit<T, 'children' | 'child'>;

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(
    href: NonNullable<LinkComponentBaseProps['href']>,
): string {
    return typeof href === 'string' ? href : href.url;
}
