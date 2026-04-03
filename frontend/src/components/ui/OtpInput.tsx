import React, { useRef, useState, useEffect } from 'react';

interface OtpInputProps {
  length?: number;
  value: string;
  onChange: (value: string) => void;
  disabled?: boolean;
}

export default function OtpInput({ length = 6, value, onChange, disabled = false }: OtpInputProps) {
  const [digits, setDigits] = useState<string[]>(new Array(length).fill(''));
  const inputRefs = useRef<(HTMLInputElement | null)[]>([]);

  // Initialize digits from external value
  useEffect(() => {
    if (value.length === length) {
      setDigits(value.split(''));
    }
  }, [value, length]);

  const handleChange = (index: number, val: string) => {
    if (disabled) return;
    
    // Only accept numbers
    const cleanVal = val.replace(/[^0-9]/g, '').slice(-1);
    
    const newDigits = [...digits];
    newDigits[index] = cleanVal;
    setDigits(newDigits);
    
    const combinedValue = newDigits.join('');
    onChange(combinedValue);

    // Auto-focus next input
    if (cleanVal && index < length - 1) {
      inputRefs.current[index + 1]?.focus();
    }
  };

  const handleKeyDown = (index: number, e: React.KeyboardEvent<HTMLInputElement>) => {
    if (disabled) return;

    if (e.key === 'Backspace' && !digits[index] && index > 0) {
      inputRefs.current[index - 1]?.focus();
    }
  };

  const handlePaste = (e: React.ClipboardEvent) => {
    if (disabled) return;
    e.preventDefault();
    const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, length);
    
    if (pastedData) {
      const newDigits = pastedData.split('').concat(new Array(length - pastedData.length).fill('')).slice(0, length);
      setDigits(newDigits);
      onChange(newDigits.join(''));
      
      // Focus last filled or last input
      const nextIndex = Math.min(pastedData.length, length - 1);
      inputRefs.current[nextIndex]?.focus();
    }
  };

  return (
    <div className="flex justify-between gap-2 md:gap-3" onPaste={handlePaste} dir="ltr">
      {digits.map((digit, index) => (
        <input
          key={index}
          ref={(el) => { inputRefs.current[index] = el; }}
          type="text"
          inputMode="numeric"
          autoComplete="one-time-code"
          value={digit}
          onChange={(e) => handleChange(index, e.target.value)}
          onKeyDown={(e) => handleKeyDown(index, e)}
          disabled={disabled}
          className={`w-11 h-14 md:w-14 md:h-16 text-center text-xl md:text-2xl font-black rounded-2xl border-2 transition-all duration-300 outline-none
            ${disabled ? 'bg-zinc-50 border-zinc-100 text-zinc-300' : 
              digit 
                ? 'bg-white border-[#ba1d20] text-[#ba1d20] shadow-[0_0_15px_-3px_rgba(186,29,32,0.15)] ring-4 ring-[#ba1d20]/5' 
                : 'bg-zinc-50/50 border-zinc-100 text-zinc-800 focus:border-zinc-300 focus:bg-white focus:ring-4 focus:ring-zinc-100'
            }`}
        />
      ))}
    </div>
  );
}
