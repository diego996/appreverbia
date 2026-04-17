import './bootstrap';
import 'bootstrap';

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js');
  });
}

const preventZoomGestures = () => {
  let lastTouchEnd = 0;

  document.addEventListener('touchstart', (event) => {
    if (event.touches.length > 1) {
      event.preventDefault();
    }
  }, { passive: false });

  document.addEventListener('touchmove', (event) => {
    if (event.touches.length > 1) {
      event.preventDefault();
    }
  }, { passive: false });

  document.addEventListener('touchend', (event) => {
    const now = Date.now();
    if (now - lastTouchEnd <= 300) {
      event.preventDefault();
    }
    lastTouchEnd = now;
  }, { passive: false });

  document.addEventListener('gesturestart', (event) => event.preventDefault(), { passive: false });
  document.addEventListener('gesturechange', (event) => event.preventDefault(), { passive: false });
  document.addEventListener('gestureend', (event) => event.preventDefault(), { passive: false });

  window.addEventListener('wheel', (event) => {
    if (event.ctrlKey) {
      event.preventDefault();
    }
  }, { passive: false });
};

const setupOrientationLock = () => {
  const applyOrientationState = () => {
    const isMobileViewport = window.matchMedia('(max-width: 1024px)').matches;
    const isLandscape = window.matchMedia('(orientation: landscape)').matches;
    document.documentElement.classList.toggle('orientation-locked-landscape', isMobileViewport && isLandscape);
  };

  const tryLockPortrait = async () => {
    if (!window.screen?.orientation?.lock) return;
    try {
      await window.screen.orientation.lock('portrait');
    } catch (_) {
      // Best effort only; many browsers require fullscreen/gesture.
    }
  };

  applyOrientationState();
  tryLockPortrait();

  window.addEventListener('resize', applyOrientationState);
  window.addEventListener('orientationchange', applyOrientationState);
  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
      applyOrientationState();
      tryLockPortrait();
    }
  });
};

const initMobileHardening = () => {
  if (window.__reverbiaMobileHardeningInitialized) return;
  window.__reverbiaMobileHardeningInitialized = true;
  preventZoomGestures();
  setupOrientationLock();
};

document.addEventListener('DOMContentLoaded', initMobileHardening);
document.addEventListener('livewire:navigated', initMobileHardening);
