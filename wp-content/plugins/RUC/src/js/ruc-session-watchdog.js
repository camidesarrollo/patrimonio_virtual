(function () {
  'use strict';

  // Evitar doble inicialización
  if (window.__rucWatchdogInitialized) {
    console.warn('[RUC] Watchdog ya inicializado');
    return;
  }
  window.__rucWatchdogInitialized = true;

  // Configuración
  const CONFIG = {
    inactivityTime: 5 * 60 * 1000,    // 5 minutos de inactividad
    logIntervalTime: 60 * 1000,       // Log cada 1 minuto
    pingEndpoint: rucWatchdog.restUrl + 'ruc/v1/ping',
    loginUrl: rucWatchdog.loginUrl
  };

  // Estado
  let inactivityTimer = null;
  let logInterval = null;
  let remainingTime = CONFIG.inactivityTime;
  let isActive = true;

  /**
   * Reinicia el temporizador de inactividad
   */
  function resetTimer() {
    if (!isActive) return;

    clearTimeout(inactivityTimer);
    clearInterval(logInterval);

    remainingTime = CONFIG.inactivityTime;

    // Timer principal de inactividad
    inactivityTimer = setTimeout(onInactivity, CONFIG.inactivityTime);

    // Log periódico del tiempo restante
    logInterval = setInterval(() => {
      remainingTime -= CONFIG.logIntervalTime;

      if (remainingTime > 0) {
        const minutesLeft = Math.ceil(remainingTime / 60000);
        console.log(`[RUC] Inactividad: ${minutesLeft} minuto(s) restante(s)`);
      } else {
        clearInterval(logInterval);
      }
    }, CONFIG.logIntervalTime);
  }

  /**
   * Maneja la inactividad del usuario
   */
  function onInactivity() {
    clearInterval(logInterval);

    console.log('[RUC] Usuario inactivo. Verificando sesión...');

    fetch(CONFIG.pingEndpoint, {
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-WP-Nonce': rucWatchdog.nonce
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.status === 'ok') {
        console.log('[RUC] Sesión válida');
        // Reiniciar el temporizador para continuar monitoreando
        resetTimer();
      } else if (data.error) {
        console.warn('[RUC] Sesión expirada:', data.error);
        redirectToLogin();
      }
    })
    .catch(error => {
      console.error('[RUC] Error al verificar sesión:', error);
      // En caso de error de red, redirigir al login por seguridad
      redirectToLogin();
    });
  }

  /**
   * Redirige al login
   */
  function redirectToLogin() {
    isActive = false;
    clearTimeout(inactivityTimer);
    clearInterval(logInterval);

    console.log('[RUC] Redirigiendo al login...');
    
    // Agregar parámetro para mostrar mensaje de sesión expirada
    const separator = CONFIG.loginUrl.includes('?') ? '&' : '?';
    window.location.href = CONFIG.loginUrl + separator + 'ruc_expired=1';
  }

  /**
   * Registra eventos de actividad del usuario
   */
  function registerActivityListeners() {
    const events = [
      'mousemove',
      'mousedown', 
      'keydown',
      'click',
      'scroll',
      'touchstart',
      'touchmove'
    ];

    events.forEach(eventName => {
      document.addEventListener(eventName, resetTimer, { 
        passive: true,
        capture: true 
      });
    });
  }

  /**
   * Limpia recursos al cerrar/recargar la página
   */
  function cleanup() {
    clearTimeout(inactivityTimer);
    clearInterval(logInterval);
    isActive = false;
  }

  /**
   * Inicialización
   */
  function init() {
    // Verificar que tenemos las variables necesarias
    if (typeof rucWatchdog === 'undefined') {
      console.error('[RUC] Variables de configuración no encontradas');
      return;
    }

    console.log('[RUC] Watchdog iniciado');
    
    // Registrar listeners de actividad
    registerActivityListeners();
    
    // Cleanup al salir
    window.addEventListener('beforeunload', cleanup);
    
    // Iniciar el temporizador
    resetTimer();
  }

  // Esperar a que el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();