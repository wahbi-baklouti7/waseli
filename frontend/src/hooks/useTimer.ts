import { useState, useEffect, useCallback } from 'react';

export const useTimer = (initialSeconds: number = 0) => {
  const [countdown, setCountdown] = useState(initialSeconds);

  useEffect(() => {
    let timer: ReturnType<typeof setTimeout>;
    if (countdown > 0) {
      timer = setTimeout(() => setCountdown(countdown - 1), 1000);
    }
    return () => clearTimeout(timer);
  }, [countdown]);

  const resetTimer = useCallback((seconds: number = initialSeconds) => {
    setCountdown(seconds);
  }, [initialSeconds]);

  const formatTime = useCallback((seconds: number) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${s.toString().padStart(2, '0')}`;
  }, []);

  return {
    countdown,
    resetTimer,
    formatTime,
    isTimerFinished: countdown === 0
  };
};
