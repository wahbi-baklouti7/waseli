import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useForm, Controller } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../hooks/useAuth';
import { registerSchema } from '../types/auth';
import type { RegisterFormData } from '../types/auth';
import LanguageSwitcher from '../components/ui/LanguageSwitcher';
import { COUNTRY_CODES } from '../constants/countries';
import { TUNISIAN_REGIONS } from '../constants/tunisia';
import Input from '../components/ui/Input';
import { Select, SelectItem } from '../components/ui/Select';

export default function Register() {
  const { t, i18n } = useTranslation();
  const [role, setRole] = useState<'buyer' | 'carrier'>('buyer');
  const { loading, register: registerUser } = useAuth();

  const {
    register,
    handleSubmit,
    setValue,
    watch,
    control,
    setError,
    formState: { errors },
  } = useForm<RegisterFormData>({
    resolver: zodResolver(registerSchema),
    defaultValues: {
      role: 'buyer',
      country_code: '+216',
    },
  });

  const selectedCountryCode = watch('country_code');

  const handleRoleChange = (newRole: 'buyer' | 'carrier') => {
    setRole(newRole);
    setValue('role', newRole);
  };

  const onSubmit = async (data: RegisterFormData) => {
    const result = await registerUser(data);
    
    if (result && !result.success && result.errors) {
      Object.keys(result.errors).forEach((key) => {
        setError(key as any, {
          type: 'server',
          message: result.errors?.[key][0],
        });
      });
    }
  };

  return (
    <main className="flex-grow flex flex-col items-center justify-center p-4 bg-zinc-50 font-body text-zinc-800 min-h-screen">
      <LanguageSwitcher />

      <div className="max-w-lg md:max-w-xl lg:max-w-2xl w-full bg-white rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.12)] overflow-hidden relative z-10 border border-zinc-100">
        <div className="p-6 md:p-10 lg:p-12">
          <div className="mb-8 text-center">
            <h2 className="font-headline text-3xl font-black italic text-[#ba1d20] tracking-tighter inline-block">
              Wasitni
            </h2>
          </div>

          <header className="flex flex-col items-center mb-10 text-center max-w-md mx-auto">
            <h1 className="font-headline text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900 mb-3 balance">
              {t('auth.register_title')}
            </h1>
            <p className="text-zinc-500 text-sm leading-relaxed max-w-xs text-pretty font-medium">
              {t('auth.register_desc')}
            </p>
          </header>

          {/* {errorMessage && (
            <div className="mb-6 p-3.5 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-bold flex items-center gap-2.5 animate-shake">
              <span className="material-symbols-outlined text-[18px]" data-icon="error">error</span>
              {errorMessage}
            </div>
          )} */}

          <div className="flex flex-col gap-5 mb-11 max-w-sm mx-auto">
            <div className="bg-zinc-100/80 p-1.5 rounded-2xl flex border border-zinc-200/50 relative overflow-hidden backdrop-blur-sm shadow-inner">
              <div 
                className={`absolute top-1.5 bottom-1.5 w-[calc(50%-6px)] bg-white rounded-xl shadow-[0_4px_12px_-2px_rgba(0,0,0,0.12)] border border-black/5 transition-all duration-500 cubic-bezier(0.4, 0, 0.2, 1) z-0 ${
                  role === 'buyer' 
                    ? (i18n.language === 'ar' ? 'translate-x-0 right-1.5 ring-1 ring-[#ba1d20]/10' : 'translate-x-0 left-1.5 ring-1 ring-[#ba1d20]/10') 
                    : (i18n.language === 'ar' ? '-translate-x-[calc(100%+0px)] right-1.5 ring-1 ring-indigo-500/10' : 'translate-x-[calc(100%+0px)] left-1.5 ring-1 ring-indigo-500/10')
                }`}
              ></div>
              
              <button 
                onClick={() => handleRoleChange('buyer')}
                className={`flex-1 py-3.5 px-4 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2.5 relative z-10 ${
                  role === 'buyer' ? 'text-[#ba1d20]' : 'text-zinc-400 hover:text-zinc-600'
                }`}
                type="button"
                aria-pressed={role === 'buyer'}
              >
                <div className={`w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500 ${role === 'buyer' ? 'bg-[#ba1d20]/10 scale-110 shadow-sm' : 'bg-transparent'}`}>
                  <span className="material-symbols-outlined text-[20px]" data-icon="shopping_bag">shopping_bag</span>
                </div>
                {t('auth.role_buyer', 'buyer')}
              </button>

              <button 
                onClick={() => handleRoleChange('carrier')}
                className={`flex-1 py-3.5 px-4 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2.5 relative z-10 ${
                  role === 'carrier' ? 'text-indigo-700' : 'text-zinc-400 hover:text-zinc-600'
                }`}
                type="button"
                aria-pressed={role === 'carrier'}
              >
                <div className={`w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500 ${role === 'carrier' ? 'bg-indigo-50 scale-110 shadow-sm' : 'bg-transparent'}`}>
                  <span className="material-symbols-outlined text-[20px]" data-icon="local_shipping">local_shipping</span>
                </div>
                {t('auth.role_traveler', 'Porteur')}
              </button>
            </div>
            
            <div className={`text-center transition-all duration-500 transform ${role === 'buyer' ? 'text-[#ba1d20]' : 'text-indigo-600'}`}>
              <p className="text-[10px] uppercase tracking-[0.2em] font-black italic opacity-90 mb-1">
                {role === 'buyer' ? t('auth.buyer_mode', 'Mode buyer') : t('auth.traveler_mode', 'Mode Voyageur')}
              </p>
              <p className="text-[10px] font-bold text-zinc-400 max-w-[240px] mx-auto leading-relaxed">
                {role === 'buyer' 
                  ? t('auth.buyer_hint', 'Je veux acheter et recevoir des articles') 
                  : t('auth.traveler_hint', 'Je voyage et je veux transporter des articles')}
              </p>
            </div>
          </div>

          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input 
                  {...register('first_name')}
                  label={t('common.fullName', 'NOM COMPLET')}
                  placeholder={t('common.firstName', 'Prénom') + '…'}
                  icon="person"
                  error={errors.first_name?.message ? t(errors.first_name.message as string) : undefined}
                />
                <div className="md:pt-[22px]"> {/* Align with first name input label height */}
                  <Input 
                    {...register('last_name')}
                    placeholder={t('common.lastName', 'Nom') + '…'}
                    icon="person"
                    error={errors.last_name?.message ? t(errors.last_name.message as string) : undefined}
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <label className="block text-[10px] font-bold uppercase tracking-[0.15em] text-zinc-400 ml-1">{t('common.phone', 'NUMÉRO WHATSAPP')}</label>
                  <div className="space-y-1">
                    <div className="relative group flex" dir="ltr">
                      <div className="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 group-focus-within:text-[#ba1d20] transition-colors pointer-events-none z-10 flex items-center justify-center">
                        <span className="material-symbols-outlined text-[20px]">call</span>
                      </div>
                      <select 
                        {...register('country_code')}
                        className="flex items-center pl-12 pr-3 font-bold text-zinc-500 border-r border-zinc-200 group-focus-within:text-zinc-800 transition-colors bg-zinc-50/50 rounded-l-xl border border-r-0 border-zinc-200 text-xs appearance-none cursor-pointer focus:outline-none min-w-[100px]"
                      >
                        {COUNTRY_CODES.map(c => (
                          <option key={c.country} value={c.code}>{c.country} {c.code}</option>
                        ))}
                      </select>
                      <input 
                        {...register('phone')}
                        className={`w-full h-12 px-5 rounded-r-xl bg-zinc-50/50 border transition text-zinc-800 font-medium placeholder:text-zinc-400 text-sm focus-visible:bg-white focus-visible:border-[#ba1d20]/50 focus-visible:ring-4 focus-visible:ring-[#ba1d20]/10 outline-none ${errors.phone ? 'border-red-400' : 'border-zinc-200 hover:border-zinc-300'}`} 
                        placeholder={selectedCountryCode === '+216' ? '00 000 000…' : '00 00 00 00…'} 
                        type="tel"
                      />
                    </div>
                    {errors.phone && <p className="text-[10px] text-red-500 font-semibold ml-1">{t(errors.phone.message as string)}</p>}
                  </div>
                </div>

                <Input 
                  {...register('email')}
                  label={t('common.email', 'E-MAIL (FACULTATIF)')}
                  placeholder="votre@email.com…"
                  type="email"
                  icon="mail"
                  error={errors.email?.message ? t(errors.email.message as string) : undefined}
                />
              </div>

              <div className={`grid grid-cols-1 ${role === 'carrier' ? 'md:grid-cols-2' : ''} gap-6`}>
                <Controller
                  name={role === 'carrier' ? "residence_country_id" : "tunisian_city_id"}
                  control={control}
                  render={({ field }) => (
                    <Select
                      value={field.value || ""}
                      onValueChange={field.onChange}
                      label={role === 'carrier' ? t('auth.residence_country', 'Pays de résidence') : t('auth.region_tunisia', 'Région en Tunisie')}
                      placeholder={t('common.select', 'Sélectionner') + '…'}
                      icon="location_on"
                      error={role === 'carrier' 
                        ? (errors.residence_country_id?.message ? t(errors.residence_country_id.message as string) : undefined)
                        : (errors.tunisian_city_id?.message ? t(errors.tunisian_city_id.message as string) : undefined)
                      }
                    >
                      {role === 'carrier' 
                        ? COUNTRY_CODES.map(country => (
                            <SelectItem key={country.id} value={country.id}>{country.label}</SelectItem>
                          ))
                        : TUNISIAN_REGIONS.map(region => (
                            <SelectItem key={region.id} value={region.id}>{region.name}</SelectItem>
                          ))
                      }
                    </Select>
                  )}
                />

                {role === 'carrier' && (
                  <Controller
                    name="tunisian_city_id"
                    control={control}
                    render={({ field }) => (
                      <Select
                        value={field.value || ""}
                        onValueChange={field.onChange}
                        label={t('auth.tunisian_city_residence', 'Ville de résidence en Tunisie')}
                        placeholder={t('common.select', 'Sélectionner') + '…'}
                        icon="apartment"
                        error={errors.tunisian_city_id?.message ? t(errors.tunisian_city_id.message as string) : undefined}
                      >
                        {TUNISIAN_REGIONS.map(region => (
                          <SelectItem key={region.id} value={region.id}>{region.name}</SelectItem>
                        ))}
                      </Select>
                    )}
                  />
                )}
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Input 
                  {...register('password')}
                  label={t('common.password', 'MOT DE PASSE')}
                  placeholder="••••••••"
                  type="password"
                  icon="lock"
                  error={errors.password?.message ? t(errors.password.message as string) : undefined}
                />
                <Input 
                  {...register('password_confirmation')}
                  label={t('common.confirmPassword', 'CONFIRMER')}
                  placeholder="••••••••"
                  type="password"
                  icon="lock"
                  error={errors.password_confirmation?.message ? t(errors.password_confirmation.message as string) : undefined}
                />
              </div>
            </div>

            <div className="flex items-start gap-3 pt-2">
              <div className="flex items-center h-5">
                <input 
                  id="terms" 
                  className="w-4 h-4 rounded-md border-zinc-300 text-[#ba1d20] focus:ring-[#ba1d20]/20 transition-all cursor-pointer" 
                  type="checkbox"
                  required
                />
              </div>
              <label className="text-[11px] text-zinc-400 leading-normal cursor-pointer select-none" htmlFor="terms">
                {t('auth.terms_accept_start', "J'accepte les")} <span className="text-red-500 font-bold">{t('auth.terms_link', "Conditions d'Utilisation")}</span> {t('auth.terms_accept_end', "et la politique de confidentialité de Wasitni.")}
              </label>
            </div>

            <button 
              className={`w-full h-14 bg-[#ba1d20] text-white font-headline font-extrabold rounded-xl shadow-[0_10px_25px_-5px_rgba(186,29,32,0.4)] hover:bg-[#a2181b] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 text-sm uppercase tracking-wider flex items-center justify-center gap-2 ${loading ? 'opacity-70 pointer-events-none' : ''}`}
              type="submit"
            >
              {loading && <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>}
              {loading ? t('common.loading', 'Inscription...') : t('common.register', "S'inscrire")}
            </button>

            <div className="text-center pt-8 mt-4 border-t border-zinc-50">
              <p className="text-zinc-400 text-xs font-semibold">
                {t('auth.already_member')} 
                <Link to="/login" className="text-red-500 font-bold px-2 hover:underline decoration-2 transition-all duration-200">
                  {t('common.login')}
                </Link>
              </p>
            </div>
          </form>
        </div>
      </div>
    </main>
  );
}
