import React, { forwardRef, useId } from 'react';
import { useTranslation } from 'react-i18next';
import { cn } from '../../lib/utils';

interface InputProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label?: string;
  error?: string;
  icon?: string;
}

const Input = forwardRef<HTMLInputElement, InputProps>(({ label, error, icon, className = '', id, ...props }, ref) => {
  const { i18n } = useTranslation();
  const isRTL = i18n.language === 'ar';
  const autoId = useId();
  const inputId = id || autoId;
  const errorId = `${inputId}-error`;

  return (
    <div className={cn("space-y-2 w-full", error && "animate-shake-minor")}>
      {label && (
        <label 
          htmlFor={inputId}
          className={cn(
            "block text-[10px] font-bold uppercase tracking-[0.15em] ml-1 cursor-pointer transition-colors",
            error ? "text-red-500" : "text-zinc-400"
          )}
        >
          {label}
        </label>
      )}
      <div className="relative group">
        {icon && (
          <span 
            className={cn(
              "material-symbols-outlined absolute top-1/2 -translate-y-1/2 text-[20px] transition-colors pointer-events-none z-10",
              error ? "text-red-400" : "text-zinc-400 group-focus-within:text-[#ba1d20]",
              isRTL ? "right-4" : "left-4"
            )} 
            data-icon={icon}
            aria-hidden="true"
          >
            {icon}
          </span>
        )}
        <input
          ref={ref}
          id={inputId}
          aria-invalid={!!error}
          aria-describedby={error ? errorId : undefined}
          className={cn(
            "w-full h-12 rounded-xl border transition text-zinc-800 font-medium placeholder:text-zinc-400 text-sm outline-none",
            "focus-visible:bg-white focus-visible:ring-4",
            error 
              ? "border-red-500/60 bg-red-50/10 focus-visible:border-red-500 focus-visible:ring-red-500/10" 
              : "border-zinc-200 bg-zinc-50/50 hover:border-zinc-300 focus-visible:border-[#ba1d20]/50 focus-visible:ring-[#ba1d20]/10",
            icon ? (isRTL ? "pr-12 pl-5" : "pl-12 pr-5") : "px-5",
            className
          )}
          {...props}
        />
      </div>
      {error && (
        <p id={errorId} className="text-[10px] text-red-500 font-semibold ml-1 animate-in fade-in slide-in-from-top-1" role="alert" aria-live="polite">
          {error}
        </p>
      )}
    </div>
  );
});

Input.displayName = 'Input';

export default Input;
