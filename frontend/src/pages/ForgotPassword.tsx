import { Link } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../hooks/useAuth';
import { useTimer } from '../hooks/useTimer';
import { forgotPasswordSchema } from '../types/auth';
import type { ForgotPasswordFormData } from '../types/auth';
import LanguageSwitcher from '../components/ui/LanguageSwitcher';
import Input from '../components/ui/Input';

export default function ForgotPassword() {
  const { t } = useTranslation();
  const { countdown, resetTimer, formatTime } = useTimer(0);
  const { loading, errorMessage, successMessage, forgotPassword } = useAuth();

  const {
    register,
    handleSubmit,
    getValues,
    formState: { errors },
  } = useForm<ForgotPasswordFormData>({
    resolver: zodResolver(forgotPasswordSchema),
  });

  const onSubmit = async (data: ForgotPasswordFormData) => {
    const success = await forgotPassword(data);
    if (success) {
      resetTimer(90);
    }
  };

  const handleResend = async () => {
    if (countdown > 0) return;
    const email = getValues('email');
    const success = await forgotPassword({ email });
    if (success) {
      resetTimer(90);
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
            <h1 className="font-headline text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900 mb-4 balance">
              {t('auth.forgot_password_title')}
            </h1>
            <p className="text-zinc-500 text-sm leading-relaxed max-w-xs text-pretty font-medium opacity-80">
              {t('auth.forgot_password_desc')}
            </p>
          </header>

          {errorMessage && (
            <div className="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-xs font-bold flex items-center gap-3 animate-shake" role="alert">
              <span className="material-symbols-outlined text-[20px]">error</span>
              {errorMessage}
            </div>
          )}

          {successMessage && (
            <div className="mb-8 p-4 bg-green-50 border border-green-100 rounded-2xl text-green-600 text-xs font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-2" role="alert">
              <span className="material-symbols-outlined text-[20px]">check_circle</span>
              {successMessage}
            </div>
          )}

          {!successMessage ? (
            <form onSubmit={handleSubmit(onSubmit)} className="space-y-8">
              <Input 
                {...register('email')}
                label={t('common.email', 'E-MAIL')}
                placeholder="votre@email.com…"
                type="email"
                icon="mail"
                error={errors.email?.message ? t(errors.email.message as string) : undefined}
                autoComplete="email"
              />

              <button 
                className={`w-full h-14 bg-[#ba1d20] text-white font-headline font-extrabold rounded-xl shadow-[0_15px_35px_-5px_rgba(186,29,32,0.4)] hover:bg-[#a2181b] hover:-translate-y-1 active:translate-y-0 transition-all duration-300 text-sm uppercase tracking-wider flex items-center justify-center gap-2 ${loading ? 'opacity-70 pointer-events-none' : ''}`}
                type="submit"
              >
                {loading && <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>}
                {loading ? t('common.loading', 'Envoi...') : t('auth.send_reset_link')}
              </button>

              <div className="text-center pt-8 mt-4 border-t border-zinc-50">
                <Link to="/login" className="inline-flex items-center gap-2 text-zinc-900 text-xs font-bold hover:text-black transition-colors group">
                  <span className="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">keyboard_backspace</span>
                  {t('auth.back_to_login')}
                </Link>
              </div>
            </form>
          ) : (
            <div className="text-center flex flex-col items-center w-full">
              
              <div className="w-full mb-8">
                <p className="text-zinc-500 text-[11px] font-bold uppercase tracking-widest opacity-60 mb-2">
                  {t('auth.not_received_email')}
                </p>
                <p className="text-zinc-400 text-[10px] leading-relaxed max-w-[240px] mx-auto font-medium mb-6">
                  {t('auth.spam_hint')}
                </p>
                
                <button
                  onClick={handleResend}
                  disabled={countdown > 0 || loading}
                  className={`w-full h-14 rounded-xl text-sm font-headline font-extrabold uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2 ${
                    countdown > 0 
                      ? 'bg-zinc-100 text-zinc-400 cursor-not-allowed border border-transparent' 
                      : 'bg-[#ba1d20] text-white shadow-[0_15px_35px_-5px_rgba(186,29,32,0.4)] hover:bg-[#a2181b] hover:-translate-y-1 active:translate-y-0'
                  }`}
                  type="button"
                >
                  {loading && <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>}
                  {!loading && countdown === 0 && <span className="material-symbols-outlined text-[18px]">forward_to_inbox</span>}
                  {countdown > 0 
                    ? `${t('auth.resend_email_timer')} ${formatTime(countdown)}` 
                    : t('auth.resend_email')}
                </button>
              </div>

              <div className="text-center pt-8 mt-2 border-t border-zinc-50 w-full">
                <Link to="/login" className="inline-flex items-center gap-2 text-zinc-900 text-xs font-bold hover:text-black transition-colors group">
                  <span className="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">keyboard_backspace</span>
                  {t('auth.back_to_login')}
                </Link>
              </div>
            </div>
          )}
          
          <div className="mt-12 text-center text-[10px] text-zinc-400 font-bold tracking-widest uppercase flex flex-col gap-2">
            <p>{t('auth.help_needed')} <Link to="/support" className="text-red-500 hover:underline">{t('auth.contact_support')}</Link></p>
            <p className="opacity-50">© 2024 Wasitni. The Digital Concierge.</p>
          </div>
        </div>
      </div>
    </main>
  );
}
