import { useEffect } from 'react';
import { Link, useSearchParams, useNavigate } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../hooks/useAuth';
import { resetPasswordSchema } from '../types/auth';
import type { ResetPasswordFormData } from '../types/auth';
import LanguageSwitcher from '../components/ui/LanguageSwitcher';
import Input from '../components/ui/Input';

export default function ResetPassword() {
  const { t } = useTranslation();
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const { loading, errorMessage, successMessage, resetPassword } = useAuth();

  const token = searchParams.get('token');
  const email = searchParams.get('email');

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<ResetPasswordFormData>({
    resolver: zodResolver(resetPasswordSchema),
    defaultValues: {
      token: token || '',
      email: email || '',
    },
  });

  useEffect(() => {
    if (!token || !email) {
      // If direct access without params, redirect to forgot password
      // But let's show an error if they are missing
    }
  }, [token, email]);

  const onSubmit = async (data: ResetPasswordFormData) => {
    const success = await resetPassword(data);
    if (success) {
      setTimeout(() => navigate('/login'), 3000);
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
              {t('auth.reset_password_title', 'Réinitialiser votre mot de passe')}
            </h1>
            <p className="text-zinc-500 text-sm leading-relaxed max-w-xs text-pretty font-medium opacity-80">
              {t('auth.reset_password_desc', 'Saisissez votre nouveau mot de passe ci-dessous.')}
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
              <input type="hidden" {...register('token')} />
              <input type="hidden" {...register('email')} />

              <Input 
                {...register('password')}
                label={t('auth.new_password', 'NOUVEAU MOT DE PASSE')}
                placeholder="••••••••"
                type="password"
                icon="lock"
                error={errors.password?.message ? t(errors.password.message as string) : undefined}
                autoComplete="new-password"
              />

              <Input 
                {...register('password_confirmation')}
                label={t('auth.confirm_new_password', 'CONFIRMER LE MOT DE PASSE')}
                placeholder="••••••••"
                type="password"
                icon="lock_reset"
                error={errors.password_confirmation?.message ? t(errors.password_confirmation.message as string) : undefined}
                autoComplete="new-password"
              />

              <button 
                className={`w-full h-14 bg-[#ba1d20] text-white font-headline font-extrabold rounded-xl shadow-[0_15px_35px_-5px_rgba(186,29,32,0.4)] hover:bg-[#a2181b] hover:-translate-y-1 active:translate-y-0 transition-all duration-300 text-sm uppercase tracking-wider flex items-center justify-center gap-2 ${loading ? 'opacity-70 pointer-events-none' : ''}`}
                type="submit"
              >
                {loading && <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>}
                {loading ? t('common.loading', 'Envoi...') : t('auth.reset_password_btn', 'Changer le mot de passe')}
              </button>
            </form>
          ) : (
            <div className="text-center py-4">
              <p className="text-zinc-500 font-medium mb-6">
                {t('auth.reset_redirect_msg', 'Vous allez être redirigé vers la page de connexion.')}
              </p>
              <Link
                to="/login"
                className="inline-flex items-center gap-2 text-[#ba1d20] text-sm font-bold hover:underline"
              >
                {t('auth.go_to_login', 'Se connecter maintenant')}
                <span className="material-symbols-outlined text-[18px]">arrow_forward</span>
              </Link>
            </div>
          )}

          <div className="mt-12 text-center text-[10px] text-zinc-400 font-bold tracking-widest uppercase flex flex-col gap-2">
            <p>© 2024 Wasitni. The Digital Concierge.</p>
          </div>
        </div>
      </div>
    </main>
  );
}
