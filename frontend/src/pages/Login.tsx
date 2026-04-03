import { Link } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../hooks/useAuth';
import { loginSchema } from '../types/auth';
import type { LoginFormData } from '../types/auth';
import LanguageSwitcher from '../components/ui/LanguageSwitcher';
import Input from '../components/ui/Input';

export default function Login() {
  const { t } = useTranslation();
  const { loading, errorMessage, login } = useAuth();

  const {
    register,
    handleSubmit,
    setError,
    formState: { errors },
  } = useForm<LoginFormData>({
    resolver: zodResolver(loginSchema),
  });

  const onSubmit = async (data: LoginFormData) => {
    const result = await login(data);
    
    if (!result.success && result.errors) {
      Object.keys(result.errors).forEach((key) => {
        const fieldName = key === 'login' ? 'email' : (key as keyof LoginFormData);
        setError(fieldName, {
          type: 'server',
          message: result.errors?.[key][0],
        });
      });
    }
  };

  return (
    <main className="flex-grow flex flex-col items-center justify-center p-4 bg-zinc-50 font-body text-zinc-800 min-h-screen">
      <LanguageSwitcher />

      <div className="max-w-md md:max-w-lg w-full bg-white rounded-[2.5rem] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.12)] overflow-hidden relative z-10 border border-zinc-100">
        <div className="p-6 md:p-10 lg:p-12">
          <div className="mb-10 text-center">
            <h2 className="font-headline text-3xl font-black italic text-[#ba1d20] tracking-tighter inline-block">
              Wasitni
            </h2>
          </div>

          <header className="flex flex-col items-center mb-12 text-center mx-auto">
            <h1 className="font-headline text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900 mb-4 balance">
              {t('auth.welcome')}
            </h1>
            <p className="text-zinc-500 text-sm leading-relaxed max-w-xs text-pretty font-medium opacity-80">
              {t('auth.welcome_desc')}
            </p>
          </header>

          {errorMessage && (
            <div className="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-xs font-bold flex flex-col md:flex-row items-start md:items-center gap-3 animate-shake" role="alert" aria-live="polite">
              <div className="flex items-center gap-3 flex-grow">
                <span className="material-symbols-outlined text-[20px]" data-icon="error">error</span>
                <span>{errorMessage}</span>
              </div>
              {errorMessage === t('auth.inactive') && (
                <Link 
                  to="/verify-email" 
                  className="w-full md:w-auto text-center bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition-all active:scale-95 shadow-sm shadow-red-200"
                >
                  {t('auth.verify_now')}
                </Link>
              )}
            </div>
          )}

          <form onSubmit={handleSubmit(onSubmit)} className="space-y-8">
            <div className="space-y-6">
              <Input 
                {...register('email')}
                label={t('common.email', 'E-MAIL')}
                placeholder="votre@email.com…"
                type="email"
                icon="mail"
                // error={errors.email?.message ? t(errors.email.message as string) : undefined}
                autoComplete="email"
              />

              <div className="space-y-2">
                <div className="flex items-center justify-between ml-1">
                  <label className="block text-[10px] font-bold uppercase tracking-[0.15em] text-zinc-400 cursor-pointer" htmlFor="password">
                    {t('common.password', 'MOT DE PASSE')}
                  </label>
                  <Link to="/forgot-password" className="text-[10px] font-bold text-red-500 hover:underline decoration-1 transition-all">
                    {t('auth.forgot_password', 'Oublié ?')}
                  </Link>
                </div>
                <Input 
                  {...register('password')}
                  id="password"
                  placeholder="••••••••"
                  type="password"
                  icon="lock"
                  // error={errors.password?.message ? t(errors.password.message as string) : undefined}
                  autoComplete="current-password"
                />
              </div>
            </div>

            <div className="flex items-center gap-3 pt-2">
              <div className="flex items-center h-5">
                <input 
                  id="remember" 
                  className="w-4 h-4 rounded-md border-zinc-300 text-[#ba1d20] focus:ring-[#ba1d20]/20 transition-all cursor-pointer" 
                  type="checkbox"
                />
              </div>
              <label className="text-xs text-zinc-400 leading-normal cursor-pointer select-none font-medium" htmlFor="remember">
                {t('auth.stay_connected', 'Rester connecté')}
              </label>
            </div>

            <button 
              className={`w-full h-14 bg-[#ba1d20] text-white font-headline font-extrabold rounded-xl shadow-[0_15px_35px_-5px_rgba(186,29,32,0.4)] hover:bg-[#a2181b] hover:-translate-y-1 active:translate-y-0 transition-all duration-300 text-sm uppercase tracking-wider flex items-center justify-center gap-2 ${loading ? 'opacity-70 pointer-events-none' : ''}`}
              type="submit"
            >
              {loading && <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>}
              {loading ? t('common.loading', 'Connexion...') : t('common.login')}
            </button>

            <div className="text-center pt-8 mt-4 border-t border-zinc-50">
              <p className="text-zinc-400 text-xs font-semibold">
                {t('auth.no_account')} 
                <Link to="/register" className="text-red-500 font-bold px-2 hover:underline decoration-2 transition-all duration-200">
                  {t('common.register')}
                </Link>
              </p>
            </div>
          </form>
        </div>
      </div>
    </main>
  );
}

