import type Echo from 'laravel-echo';
import type Pusher from 'pusher-js';

declare global {
  interface Window {
    Pusher: typeof Pusher;
    Echo: Echo<'reverb'>;
  }
}

// Función para inicializar Echo SOLO en el cliente
export async function initializeEcho() {
  // Verificar que estamos en el navegador
  if (typeof window === 'undefined') {
    console.log('SSR: Saltando inicialización de Echo');
    return null;
  }

  // Evitar inicialización múltiple
  if (window.Echo) {
    console.log('Echo ya está inicializado');
    return window.Echo;
  }

  try {
    // Importación dinámica para evitar problemas en SSR
    const [{ default: Pusher }, { default: Echo }] = await Promise.all([
      import('pusher-js'),
      import('laravel-echo')
    ]);

    window.Pusher = Pusher;

    const echo = new Echo<'reverb'>({
      broadcaster: 'reverb',
      key: import.meta.env.VITE_REVERB_APP_KEY as string,
      wsHost: import.meta.env.VITE_REVERB_HOST as string,
      wsPort: Number(import.meta.env.VITE_REVERB_PORT ?? 80),
      wssPort: Number(import.meta.env.VITE_REVERB_PORT ?? 443),
      forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
      enabledTransports: ['ws', 'wss'],
      authEndpoint: '/broadcasting/auth',
    });

    window.Echo = echo;
    console.log('✅ Echo inicializado correctamente');
    return echo;
  } catch (error) {
    console.error('❌ Error inicializando Echo:', error);
    return null;
  }
}

// Exportar una función para obtener Echo (inicialización diferida)
export function getEcho() {
  if (typeof window === 'undefined') {
    return null;
  }
  return window.Echo;
}

// Exportación por defecto para conveniencia (inicialización diferida)
export default {
  initialize: initializeEcho,
  getInstance: getEcho,
};