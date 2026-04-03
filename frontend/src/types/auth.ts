import * as z from 'zod';

export interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  is_traveler: boolean;
  is_verified: boolean;
  trust_score: number;
  status: string;
}

export interface AuthResponse {
  token: string;
  user: User;
}

export const loginSchema = z.object({
  email: z.string().min(1, 'auth.error_email_required').email('auth.error_email_invalid'),
  password: z.string().min(1, 'auth.error_password_required'),
});

export type LoginFormData = z.infer<typeof loginSchema>;

export const registerSchema = z.object({
  first_name: z.string().min(2, 'auth.error_firstname_min'),
  last_name: z.string().min(2, 'auth.error_lastname_min'),
  email: z.string().email('auth.error_email_invalid').or(z.literal('')),
  country_code: z.string().min(1, 'Required'),
  phone: z.string().regex(/^\d{8,15}$/, 'auth.error_phone_invalid'),
  password: z.string().min(8, 'auth.error_password_min'),
  password_confirmation: z.string(),
  role: z.enum(['buyer', 'carrier']),
  residence_country_id: z.string().optional().nullable(),
  tunisian_city_id: z.string().optional().nullable(),
}).refine((data) => data.password === data.password_confirmation, {
  message: "auth.error_password_mismatch",
  path: ["password_confirmation"],
});

export type RegisterFormData = z.infer<typeof registerSchema>;

export const forgotPasswordSchema = z.object({
  email: z.string().min(1, 'auth.error_email_required').email('auth.error_email_invalid'),
});

export type ForgotPasswordFormData = z.infer<typeof forgotPasswordSchema>;

export const resetPasswordSchema = z.object({
  token: z.string().min(1, 'Token is required'),
  email: z.string().email('auth.error_email_invalid'),
  password: z.string().min(8, 'auth.error_password_min'),
  password_confirmation: z.string(),
}).refine((data) => data.password === data.password_confirmation, {
  message: "auth.error_password_mismatch",
  path: ["password_confirmation"],
});

export type ResetPasswordFormData = z.infer<typeof resetPasswordSchema>;
