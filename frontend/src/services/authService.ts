import api from '../lib/api';
import type { 
  LoginFormData, 
  RegisterFormData, 
  ForgotPasswordFormData,
  ResetPasswordFormData,
  AuthResponse
} from '../types/auth';

export const authService = {
  async register(data: RegisterFormData): Promise<AuthResponse> {
    const fullData = {
      ...data,
      phone: `${data.country_code}${data.phone.replace(/^0+/, '')}`
    };
    const response = await api.post('/v1/register', fullData);
    // Explicitly return the nested data object
    return response.data.data;
  },

  async login(data: LoginFormData): Promise<AuthResponse> {
    const response = await api.post('/v1/login', data);
    // Explicitly return the nested data object
    return response.data.data;
  },

  async forgotPassword(data: ForgotPasswordFormData): Promise<void> {
    await api.post('/v1/forgot-password', data);
  },

  async resetPassword(data: ResetPasswordFormData): Promise<void> {
    await api.post('/v1/reset-password', data);
  },

  async verifyOtp(code: string, email?: string): Promise<void> {
    await api.post('/v1/email/verify-otp', { code, email });
  },

  async resendVerificationEmail(email?: string): Promise<void> {
    await api.post('/v1/email/resend', { email });
  },

  async logout(): Promise<void> {
    await api.post('/v1/logout');
  },

  async getMe(): Promise<any> {
    const response = await api.get('/v1/me');
    return response.data;
  }
};
