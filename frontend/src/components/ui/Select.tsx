import * as React from 'react';
import * as SelectPrimitive from '@radix-ui/react-select';
import { useTranslation } from 'react-i18next';
import { cn } from '../../lib/utils';

interface SelectProps extends React.ComponentPropsWithoutRef<typeof SelectPrimitive.Root> {
  label?: string;
  placeholder?: string;
  error?: string;
  className?: string;
  icon?: string;
  // Options passed directly for simplicity or use SelectItem manually
  options?: { value: string; label: string }[];
}

const Select = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.Trigger>,
  SelectProps
>(({ label, placeholder, error, options, children, className, icon, ...props }, ref) => {
  const { i18n } = useTranslation();
  const isRTL = i18n.language === 'ar';

  return (
    <div className={cn("space-y-2 w-full", error && "animate-shake-minor")}>
      {label && (
        <label className={cn(
          "block text-[10px] font-bold uppercase tracking-[0.15em] ml-1 cursor-pointer transition-colors",
          error ? "text-red-500" : "text-zinc-400"
        )}>
          {label}
        </label>
      )}
      
      <SelectPrimitive.Root {...props}>
        <SelectPrimitive.Trigger
          ref={ref}
          className={cn(
            "flex h-12 w-full flex-row items-center justify-between rounded-xl border py-2 text-sm transition focus-visible:ring-4 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 relative group",
            error 
              ? "border-red-500/60 bg-red-50/10 focus-visible:border-red-500 focus-visible:ring-red-500/10" 
              : "border-zinc-200 bg-zinc-50/50 hover:border-zinc-300 focus-visible:border-[#ba1d20]/50 focus-visible:ring-[#ba1d20]/10",
            icon ? (isRTL ? "pl-5 pr-12" : "pl-12 pr-5") : "px-5",
            isRTL ? "text-right" : "text-left",
            className
          )}
        >
          {icon && (
            <span 
              className={cn(
                "material-symbols-outlined absolute top-1/2 -translate-y-1/2 text-[20px] transition-colors pointer-events-none",
                error ? "text-red-400" : "text-zinc-400 group-focus:text-[#ba1d20]",
                isRTL ? "right-4" : "left-4"
              )} 
            >
              {icon}
            </span>
          )}
          <SelectPrimitive.Value placeholder={placeholder || "Sélectionner..."} />
          <SelectPrimitive.Icon asChild>
            <span className={cn(
              "material-symbols-outlined text-zinc-400 text-[18px]",
              isRTL ? "mr-2" : "ml-2"
            )}>expand_more</span>
          </SelectPrimitive.Icon>
        </SelectPrimitive.Trigger>

        <SelectPrimitive.Portal>
          <SelectPrimitive.Content
            className={cn(
              "relative z-50 min-w-[8rem] overflow-hidden rounded-xl border border-zinc-200 bg-white text-zinc-900 shadow-[0_15px_50px_-15px_rgba(0,0,0,0.15)] animate-in fade-in zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2",
              isRTL ? "text-right" : "text-left"
            )}
            dir={isRTL ? 'rtl' : 'ltr'}
          >
            <SelectPrimitive.ScrollUpButton className="flex cursor-default items-center justify-center py-1">
              <span className="material-symbols-outlined text-[16px]">keyboard_arrow_up</span>
            </SelectPrimitive.ScrollUpButton>

            <SelectPrimitive.Viewport className="p-1.5 focus-visible:outline-none">
              {options ? options.map((opt) => (
                <SelectItem key={opt.value} value={opt.value}>
                  {opt.label}
                </SelectItem>
              )) : children}
            </SelectPrimitive.Viewport>

            <SelectPrimitive.ScrollDownButton className="flex cursor-default items-center justify-center py-1">
              <span className="material-symbols-outlined text-[16px]">keyboard_arrow_down</span>
            </SelectPrimitive.ScrollDownButton>
          </SelectPrimitive.Content>
        </SelectPrimitive.Portal>
      </SelectPrimitive.Root>

      {error && (
        <p className="text-[10px] text-red-500 font-semibold ml-1 animate-in fade-in slide-in-from-top-1" role="alert" aria-live="polite">
          {error}
        </p>
      )}
    </div>
  );
});

const SelectItem = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.Item>,
  React.ComponentPropsWithoutRef<typeof SelectPrimitive.Item>
>(({ className, children, ...props }, ref) => (
  <SelectPrimitive.Item
    ref={ref}
    className={cn(
      "relative flex w-full cursor-pointer select-none items-center rounded-lg py-2.5 px-4 text-sm font-medium text-zinc-600 outline-none focus:bg-[#ba1d20]/5 focus:text-[#ba1d20] data-[disabled]:pointer-events-none data-[disabled]:opacity-50 transition-colors",
      className
    )}
    {...props}
  >
    <SelectPrimitive.ItemText>{children}</SelectPrimitive.ItemText>
    <span className="absolute right-4 flex h-3.5 w-3.5 items-center justify-center">
      <SelectPrimitive.ItemIndicator>
        <span className="material-symbols-outlined text-[16px] text-[#ba1d20]">check</span>
      </SelectPrimitive.ItemIndicator>
    </span>
  </SelectPrimitive.Item>
));

Select.displayName = 'Select';
SelectItem.displayName = 'SelectItem';

export { Select, SelectItem };
