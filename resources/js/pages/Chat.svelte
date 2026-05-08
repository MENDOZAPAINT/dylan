<script lang="ts">
    import { onMount, onDestroy, tick } from 'svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Card, CardContent, CardHeader } from '@/components/ui/card';
    import { Alert, AlertDescription } from '@/components/ui/alert';
    import { Badge } from '@/components/ui/badge';
    import { ScrollArea } from '@/components/ui/scroll-area';
    import { Separator } from '@/components/ui/separator';
    import { page } from '@inertiajs/svelte';
    import { initializeEcho } from '@/echo';

    import CardFooter from '@/components/ui/card/CardFooter.svelte';

    interface Mensaje {
        id: string;
        texto: string;
        usuario: string;
        timestamp: Date;
    }

    interface EventoNewChat {
        id?: string;
        mensaje: string;
        usuario: string;
        timestamp?: string;
    }

    const ENDPOINT_ENVIAR = '/enviar-mensaje';
    const CANAL_CHAT = 'chat';
    const EVENTO_CHAT = '.new-chat';
    const ERROR_TIMEOUT_MS = 3000;
    const MENSAJE_MAX_LENGTH = 1000;

    let mensajes = $state<Mensaje[]>([]);
    let textoEntrada = $state('');
    let enviando = $state(false);
    let errorMsg = $state<string | null>(null);
    let conectado = $state(false);
    let montado = $state(false);
    let usuarioActual = $state('');
    let channel: any = null;
    let reconnectTimer: ReturnType<typeof setTimeout> | null = null;
    let intentosConexion = 0;

    let scrollRef: HTMLDivElement;

    const user = $derived(page.props.auth.user);

    onMount(() => {
        montado = true;
        usuarioActual = user?.name ?? generarNombreUsuario();
        conectarWebSocket();
    });

    onDestroy(() => {
        montado = false;
        limpiarReconexion();
        desconectarWebSocket();
    });

    function generarNombreUsuario(): string {
        return `Usuario_${Math.floor(Math.random() * 10000)}`;
    }

    async function conectarWebSocket(): Promise<void> {
        if (typeof window === 'undefined' || channel) {
            return;
        }

        errorMsg = null;

        try {
            const echo = window.Echo ?? (await initializeEcho());

            if (!echo || !montado) {
                programarReconexion();
                return;
            }

            const chatChannel = echo.channel(CANAL_CHAT);
            chatChannel.listen(EVENTO_CHAT, recibirMensaje);
            chatChannel.subscribed(onConectado);
            chatChannel.error?.(onErrorWebSocket);
            channel = chatChannel;
        } catch (err) {
            setError(err);
            conectado = false;
            programarReconexion();
        }
    }

    function desconectarWebSocket(): void {
        if (channel && typeof window !== 'undefined') {
            window.Echo.leaveChannel(CANAL_CHAT);
            channel = null;
        }
        conectado = false;
    }

    function onConectado(): void {
        intentosConexion = 0;
        conectado = true;
        errorMsg = null;
    }

    function onErrorWebSocket(): void {
        errorMsg = 'No se pudo conectar con Reverb';
        desconectarWebSocket();
        programarReconexion();
    }

    function programarReconexion(): void {
        if (!montado || reconnectTimer) return;

        intentosConexion += 1;
        const delay = Math.min(1000 * intentosConexion, 5000);

        reconnectTimer = setTimeout(() => {
            reconnectTimer = null;
            conectarWebSocket();
        }, delay);
    }

    function limpiarReconexion(): void {
        if (!reconnectTimer) return;
        clearTimeout(reconnectTimer);
        reconnectTimer = null;
    }

    function recibirMensaje(evento: EventoNewChat): void {
        const id = evento.id ?? crypto.randomUUID();
        if (mensajes.some((m) => m.id === id)) return;

        mensajes = [
            ...mensajes,
            {
                id,
                texto: evento.mensaje,
                usuario: evento.usuario,
                timestamp: evento.timestamp
                    ? new Date(evento.timestamp)
                    : new Date(),
            },
        ];
    }

    async function enviarMensaje(): Promise<void> {
        const texto = textoEntrada.trim();
        if (!texto || enviando) return;

        if (texto.length > MENSAJE_MAX_LENGTH) {
            errorMsg = `El mensaje no puede superar ${MENSAJE_MAX_LENGTH} caracteres`;
            return;
        }

        enviando = true;
        errorMsg = null;
        textoEntrada = '';

        try {
            const response = await fetch(ENDPOINT_ENVIAR, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    mensaje: texto,
                    usuario: usuarioActual,
                }),
            });

            if (!response.ok) {
                textoEntrada = texto;
                throw new Error('No se pudo enviar el mensaje');
            }
        } catch (err) {
            setError(err);
            setTimeout(() => (errorMsg = null), ERROR_TIMEOUT_MS);
        } finally {
            enviando = false;
        }
    }

    function getCsrfToken(): string {
        return (
            (
                document.querySelector(
                    'meta[name="csrf-token"]',
                ) as HTMLMetaElement
            )?.content ?? ''
        );
    }

    function formatearHora(fecha: Date): string {
        return fecha.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    function setError(err: unknown): void {
        errorMsg =
            err instanceof Error ? err.message : 'Ocurrió un error inesperado';
    }

    function onKeyPress(e: KeyboardEvent): void {
        if (e.key === 'Enter') {
            e.preventDefault();
            enviarMensaje();
        }
    }

    function onInput(e: Event): void {
        textoEntrada = (e.target as HTMLInputElement).value;
    }

    const puedeEnviar = $derived(
        textoEntrada.trim().length > 0 && conectado && !enviando,
    );

    const estadoConexion = $derived(conectado ? 'Online' : 'Conectando');
    const nombreVisible = $derived(user?.name ?? usuarioActual);

    const esMio = (usuario: string) =>
        usuario === (user?.name ?? usuarioActual);

    function scrollToBottom() {
        if (!scrollRef) return;
        scrollRef.scrollTop = scrollRef.scrollHeight;
    }

    $effect(() => {
        mensajes.length;
        tick().then(scrollToBottom);
    });
</script>

{#snippet mensajeItem(mensaje: Mensaje)}
    <div
        class="flex flex-col gap-1 max-w-[75%]
    {esMio(mensaje.usuario) ? 'ml-auto items-end' : 'items-start'}"
    >
        <div
            class="relative rounded-2xl px-4 py-2 text-sm shadow-sm pr-10
        {esMio(mensaje.usuario)
                ? 'bg-primary text-primary-foreground rounded-br-md'
                : 'bg-muted rounded-bl-md'}"
        >
            {mensaje.texto}

            <span class="absolute bottom-1 right-2 text-[10px] opacity-70">
                {formatearHora(mensaje.timestamp)}
            </span>
        </div>
    </div>
{/snippet}

<div class="flex flex-col gap-3 max-w-2xl mx-auto">
    <Card class="flex flex-col h-[80vh]">
        <!-- HEADER -->
        <CardHeader
            class="flex flex-row items-center justify-between space-y-0"
        >
            <div class="flex flex-col">
                <h2 class="text-base font-semibold">Chat en tiempo real</h2>
                <span class="text-xs text-muted-foreground">
                    {nombreVisible}
                </span>
            </div>

            <div class="flex items-center gap-2">
                <Badge
                    variant={conectado ? 'default' : 'secondary'}
                    class="h-2 w-2 rounded-full p-0"
                />
                <span class="text-xs text-muted-foreground">
                    {estadoConexion}
                </span>
            </div>
        </CardHeader>

        <Separator />

        <!-- MENSAJES -->
        <CardContent class="flex-1 overflow-hidden p-0">
            <ScrollArea class="h-full px-4 py-4">
                <div bind:this={scrollRef} class="flex flex-col gap-3">
                    {#if mensajes.length === 0}
                        <div
                            class="flex flex-col items-center justify-center h-full text-muted-foreground"
                        >
                            <p class="text-sm">Sin mensajes</p>
                            <p class="text-xs">Empieza la conversación</p>
                        </div>
                    {:else}
                        {#each mensajes as mensaje (mensaje.id)}
                            {@render mensajeItem(mensaje)}
                        {/each}
                    {/if}
                </div>
            </ScrollArea>
        </CardContent>

        <Separator />

        <!-- INPUT -->
        <CardFooter class="flex items-center gap-2">
            <Input
                value={textoEntrada}
                oninput={onInput}
                onkeypress={onKeyPress}
                placeholder="Escribe un mensaje..."
                disabled={!conectado || enviando}
                maxlength={MENSAJE_MAX_LENGTH}
            />

            <Button onclick={enviarMensaje} disabled={!puedeEnviar}>
                Enviar
            </Button>
        </CardFooter>
    </Card>

    {#if !conectado && !errorMsg && montado}
        <Alert>
            <AlertDescription>Conectando al servidor...</AlertDescription>
        </Alert>
    {/if}

    {#if errorMsg}
        <Alert variant="destructive">
            <AlertDescription>
                {errorMsg}
            </AlertDescription>
        </Alert>
    {/if}
</div>
