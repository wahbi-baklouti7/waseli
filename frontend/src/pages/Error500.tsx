import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import LanguageSwitcher from '../components/ui/LanguageSwitcher';

export default function Error500() {
  const { t } = useTranslation();

  return (
    <main className="flex-grow flex flex-col items-center justify-center p-4 bg-zinc-50 font-body text-zinc-800 min-h-screen transition-all duration-300">
      <div className="absolute top-8 right-8 z-20">
        <LanguageSwitcher />
      </div>

      <div className="max-w-md md:max-w-xl w-full bg-white rounded-[2.5rem] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.12)] overflow-hidden relative z-10 border border-zinc-100 animate-in fade-in zoom-in duration-700">
        <div className="p-8 md:p-14 text-center">
          <div className="mb-10 text-center">
            <h2 className="font-headline text-3xl font-black italic text-[#ba1d20] tracking-tighter inline-block">
              Wasitni
            </h2>
          </div>

          <div className="flex flex-col items-center mb-10">
            <div className="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mb-8 animate-pulse text-[#ba1d20]">
              <span className="material-symbols-outlined text-[40px]">settings_alert</span>
            </div>
            
            <h1 className="font-headline text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900 mb-4 text-center px-4">
              {t('error.500_title', 'Oups ! Quelque chose a mal tourné.')}
            </h1>
            <p className="text-zinc-500 text-sm leading-relaxed max-w-sm text-pretty font-medium opacity-80 mb-10 text-center px-6">
              {t('error.500_desc', 'Nous rencontrons un problème technique passager. Notre équipe de conciergerie a été alertée et travaille au rétablissement du service.')}
            </p>

            <div className="flex flex-col sm:flex-row gap-4 w-full">
              <button 
                onClick={() => window.location.reload()}
                className="flex-1 h-14 bg-[#ba1d20] text-white font-headline font-extrabold rounded-xl shadow-[0_15px_35px_-5px_rgba(186,29,32,0.4)] hover:bg-[#a2181b] hover:-translate-y-1 active:translate-y-0 transition-all duration-300 text-sm uppercase tracking-wider flex items-center justify-center gap-2"
              >
                <span className="material-symbols-outlined text-[20px]">refresh</span>
                {t('error.try_again', 'Réessayer')}
              </button>
              
              <Link 
                to="/login"
                className="flex-1 h-14 bg-zinc-900 text-white font-headline font-extrabold rounded-xl shadow-[0_15px_35px_-5px_rgba(0,0,0,0.2)] hover:bg-black hover:-translate-y-1 active:translate-y-0 transition-all duration-300 text-sm uppercase tracking-wider flex items-center justify-center gap-2"
              >
                <span className="material-symbols-outlined text-[20px]">first_page</span>
                {t('error.back_to_login', 'Retour')}
              </Link>
            </div>
          </div>

          <div className="text-center text-[10px] text-zinc-400 font-bold tracking-widest uppercase flex flex-col gap-2">
            <p className="opacity-50 mt-4">© 2024 Wasitni. The Digital Concierge.</p>
          </div>
        </div>
      </div>
      
      {/* Decorative background elements */}
      <div className="fixed top-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#ba1d20]/5 rounded-full blur-[120px] -z-0"></div>
      <div className="fixed bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-[#ba1d20]/5 rounded-full blur-[120px] -z-0"></div>
    </main>
  );
}
