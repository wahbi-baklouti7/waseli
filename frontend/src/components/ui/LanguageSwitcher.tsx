import { useTranslation } from 'react-i18next';
import { useState, useRef, useEffect } from 'react';

const languages = [
  { code: 'fr', name: 'Français', flag: '🇫🇷' },
  { code: 'en', name: 'English', flag: '🇺🇸' },
  { code: 'ar', name: 'العربية', flag: '🇹🇳' },
];

export default function LanguageSwitcher() {
  const { i18n } = useTranslation();
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  const currentLanguage = languages.find(l => l.code === i18n.language) || languages[0];

  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setIsOpen(false);
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const changeLanguage = (code: string) => {
    i18n.changeLanguage(code);
    setIsOpen(false);
  };

  return (
    <div className="fixed top-6 right-6 z-50" ref={dropdownRef}>
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="flex items-center gap-2 px-4 py-2 bg-white/90 backdrop-blur-md border border-zinc-200 shadow-sm rounded-full text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition-all hover:shadow-md"
      >
        <span>{currentLanguage.flag}</span>
        <span className="hidden sm:inline">{currentLanguage.name}</span>
        <span className={`material-symbols-outlined text-[18px] transition-transform duration-200 ${isOpen ? 'rotate-180' : ''}`} data-icon="expand_more">expand_more</span>
      </button>

      {isOpen && (
        <div className="absolute right-0 mt-2 w-40 bg-white rounded-2xl shadow-xl border border-zinc-100 overflow-hidden py-1.5 animate-in fade-in slide-in-from-top-2 duration-200">
          {languages.map((lang) => (
            <button
              key={lang.code}
              onClick={() => changeLanguage(lang.code)}
              className={`w-full flex items-center justify-between px-4 py-2.5 text-sm transition-colors hover:bg-zinc-50 ${i18n.language === lang.code ? 'text-primary font-bold' : 'text-zinc-600 font-medium'}`}
            >
              <div className="flex items-center gap-3">
                <span>{lang.flag}</span>
                <span>{lang.name}</span>
              </div>
              {i18n.language === lang.code && (
                <span className="material-symbols-outlined text-[16px]" data-icon="check">check</span>
              )}
            </button>
          ))}
        </div>
      )}
    </div>
  );
}
