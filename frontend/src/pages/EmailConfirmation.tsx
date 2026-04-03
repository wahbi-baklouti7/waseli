import { useState, useRef, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../hooks/useAuth';
import { useTimer } from '../hooks/useTimer';
import LanguageSwitcher from '../components/ui/LanguageSwitcher';

export default function EmailConfirmation() {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { countdown, resetTimer, formatTime } = useTimer(90);
  const { errorMessage, successMessage, user, verifyOtp, resendVerification } = useAuth();
  
  const [otp, setOtp] = useState(['', '', '', '', '', '']);
  const [isConfirming, setIsConfirming] = useState(false);
  const [isResending, setIsResending] = useState(false);
  const [isLocked, setIsLocked] = useState(false);
  const inputRefs = useRef<(HTMLInputElement | null)[]>([]);

  // Monitor for lockout error
  useEffect(() => {
    if (errorMessage === t('auth.otp_attempts_exceeded')) {
      setIsLocked(true);
    }
  }, [errorMessage, t]);

  const handleOtpChange = (index: number, value: string) => {
    // Only allow numbers
    if (value !== '' && !/^\d+$/.test(value)) return;

    if (value.length > 1) {
      // Handle paste
      const pastedData = value.substring(0, 6).split('').filter(char => /^\d$/.test(char));
      const newOtp = [...otp];
      pastedData.forEach((char, i) => {
        if (i + index < 6) newOtp[i + index] = char;
      });
      setOtp(newOtp);
      const focusIndex = Math.min(index + pastedData.length - 1, 5);
      inputRefs.current[focusIndex]?.focus();
      return;
    }

    const newOtp = [...otp];
    newOtp[index] = value;
    setOtp(newOtp);

    // Focus next on input
    if (value && index < 5) {
      inputRefs.current[index + 1]?.focus();
    }
  };

  const handleKeyDown = (index: number, e: React.KeyboardEvent<HTMLInputElement>) => {
    // Focus previous on backspace if current is empty
    if (e.key === 'Backspace' && !otp[index] && index > 0) {
      inputRefs.current[index - 1]?.focus();
    }
  };

  const handleConfirm = async () => {
    const code = otp.join('');
    if (code.length < 6 || isLocked || isResending) return;
    
    setIsConfirming(true);
    const success = await verifyOtp(code, user?.email);
    setIsConfirming(false);

    if (success) {
      setTimeout(() => navigate('/login'), 2000);
    }
  };

  const handleResend = async () => {
    if (countdown > 0 || isConfirming || isResending) return;
    
    setIsResending(true);
    const success = await resendVerification(user?.email);
    setIsResending(false);

    if (success) {
      setIsLocked(false);
      resetTimer(90);
      setOtp(['', '', '', '', '', '']); // Clear OTP fields for the new code
    }
  };

  return (
    <main className="flex-grow flex flex-col items-center justify-center p-4 bg-zinc-50 font-body text-zinc-800 min-h-screen transition-all duration-300">
      <LanguageSwitcher />

      <div className="max-w-md md:max-w-lg w-full bg-white rounded-[2.5rem] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.12)] overflow-hidden relative z-10 border border-zinc-100 animate-in fade-in zoom-in duration-700">
        <div className="p-6 md:p-10 lg:p-12">
          <div className="mb-10 text-center">
            <h2 className="font-headline text-3xl font-black italic text-[#ba1d20] tracking-tighter inline-block">
              Wasitni
            </h2>
          </div>

          <header className="flex flex-col items-center mb-12 text-center mx-auto">
            <h1 className="font-headline text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900 mb-4 balance text-center">
              {t('auth.verify_code_title', 'Vérifiez votre e-mail')}
            </h1>
            <p className="text-zinc-500 text-sm leading-relaxed max-w-xs text-pretty font-medium opacity-80 text-center">
              {t('auth.verify_code_desc', 'Nous avons envoyé un code de confirmation à 6 chiffres à votre adresse e-mail.')}
              {user?.email && (
                <span className="block mt-2 font-bold text-zinc-900 break-all select-all">
                  {user.email}
                </span>
              )}
            </p>
          </header>

          {errorMessage && (
            <div className={`mb-8 p-4 rounded-2xl text-xs font-bold flex items-start gap-3 animate-shake ${isLocked ? 'bg-amber-50 border border-amber-100 text-amber-700' : 'bg-red-50 border border-red-100 text-red-600'}`} role="alert">
              <span className="material-symbols-outlined text-[20px] shrink-0">
                {isLocked ? 'lock' : 'error'}
              </span>
              <p className="leading-relaxed">
                {errorMessage}
              </p>
            </div>
          )}

          {successMessage && (
            <div className="mb-8 p-4 bg-green-50 border border-green-100 rounded-2xl text-green-600 text-xs font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-2" role="alert">
              <span className="material-symbols-outlined text-[20px]">check_circle</span>
              {successMessage}
            </div>
          )}

          <div className="space-y-12">
            <div className={`flex justify-between gap-2 max-w-[340px] mx-auto transition-opacity duration-300 ${isLocked ? 'opacity-40 grayscale pointer-events-none' : ''}`}>
              {otp.map((digit, index) => (
                <input
                  key={index}
                  ref={(el) => { inputRefs.current[index] = el; }}
                  type="text"
                  inputMode="numeric"
                  maxLength={1}
                  value={digit}
                  disabled={isLocked || isConfirming || isResending}
                  onChange={(e) => handleOtpChange(index, e.target.value)}
                  onKeyDown={(e) => handleKeyDown(index, e)}
                  className="w-12 h-14 md:w-14 md:h-16 text-center text-2xl font-black rounded-2xl border-2 border-zinc-100 bg-zinc-50 text-zinc-900 focus:border-[#ba1d20] focus:ring-4 focus:ring-[#ba1d20]/5 focus:bg-white transition-all outline-none"
                />
              ))}
            </div>

            <button 
              onClick={handleConfirm}
              disabled={otp.some(d => !d) || isConfirming || isResending || isLocked}
              className={`w-full h-14 bg-[#ba1d20] text-white font-headline font-extrabold rounded-xl shadow-[0_15px_35px_-5px_rgba(186,29,32,0.4)] hover:bg-[#a2181b] hover:-translate-y-1 active:translate-y-0 transition-all duration-300 text-sm uppercase tracking-wider flex items-center justify-center gap-2 ${otp.some(d => !d) || isConfirming || isResending || isLocked ? 'opacity-50 pointer-events-none grayscale shadow-none' : ''}`}
            >
              {isConfirming && <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>}
              {isLocked ? t('error.try_again_later', 'Verrouillé') : t('auth.confirm_code', 'Confirmer')}
            </button>
            
            <div className="text-center">
              <p className="text-zinc-500 text-[11px] font-bold uppercase tracking-widest opacity-60 mb-6">
                {t('auth.not_received_email', "Vous n'avez pas reçu le code ?")}
              </p>
              <button
                onClick={handleResend}
                disabled={countdown > 0 || isResending || isConfirming}
                className={`inline-flex items-center gap-2 text-sm font-bold transition-all duration-300 ${
                  countdown > 0 || isResending || isConfirming 
                    ? 'text-zinc-400 cursor-not-allowed grayscale' 
                    : 'text-[#ba1d20] hover:text-[#a2181b] hover:translate-x-1'
                }`}
              >
                {isResending ? (
                  <span className="w-4 h-4 border-2 border-[#ba1d20]/30 border-t-[#ba1d20] rounded-full animate-spin"></span>
                ) : (
                  <span className="material-symbols-outlined text-[20px]">refresh</span>
                )}
                {countdown > 0 
                  ? `${t('auth.resend_code_timer')} ${formatTime(countdown)}` 
                  : isResending ? t('common.loading') : t('auth.resend_code', 'Renvoyer le code')}
              </button>
            </div>
          </div>
          
          <div className="mt-12 text-center text-[10px] text-zinc-400 font-bold tracking-widest uppercase flex flex-col gap-2">
            <Link to="/login" className="text-zinc-400 hover:text-black transition-colors underline underline-offset-4">
              {t('auth.back_to_login', 'Retour à la connexion')}
            </Link>
            <p className="opacity-50 mt-4">© 2024 Wasitni. The Digital Concierge.</p>
          </div>
        </div>
      </div>
    </main>
  );
}
