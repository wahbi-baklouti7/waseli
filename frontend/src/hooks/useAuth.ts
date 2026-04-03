import { useState, useCallback } from 'react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { authService } from '../services/authService';
import type { 
  LoginFormData, 
  RegisterFormData, 
  ForgotPasswordFormData,
  ResetPasswordFormData
} from '../types/auth';

export const useAuth = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [user, setUser] = useState<any>(() => {
    const savedUser = localStorage.getItem('user');
    const pendingEmail = localStorage.getItem('pending_email');
    
    if (savedUser) {
      try {
        return JSON.parse(savedUser);
      } catch (e) {
        return null;
      }
    }
    
    if (pendingEmail) {
      return { email: pendingEmail, is_unverified: true };
    }
    
    return null;
  });

  const handleError = useCallback((error: any, fallbackMessageKey: string) => {
    if (error.response?.status === 429) {
      setErrorMessage(t('auth.error_too_many_requests', 'Too many attempts. Please try again later.'));
    } else if (error.response?.status === 422 && error.response.data?.errors) {
       // Specifically for Laravel validation errors
      const backendErrors = error.response.data.errors;
      // Prioritize 'login' or 'email' specific messages for the general alert
      const errorsList = Object.values(backendErrors) as string[][];
      const specificError = backendErrors.login?.[0] || backendErrors.email?.[0] || errorsList[0]?.[0];
      setErrorMessage(specificError);
      return backendErrors;
    } else if (error.response?.data?.message) {
       // Support for general message from server (e.g. 401 Unauthorized)
      setErrorMessage(error.response.data.message);
    } else {
      setErrorMessage(t(fallbackMessageKey));
    }
    return null;
  }, [t]);

  const login = useCallback(async (data: LoginFormData) => {
    setLoading(true);
    setErrorMessage(null);
    try {
      const result = await authService.login(data);
      localStorage.setItem('auth_token', result.token);
      localStorage.setItem('user', JSON.stringify(result.user));
      setUser(result.user);
      navigate('/dashboard');
      return { success: true };
    } catch (error: any) {
      const validationErrors = handleError(error, 'auth.invalid_credentials');
      
      // If unverified, store the email locally so the activation page can use it
      const isInactive = error.response?.status === 422 && 
        (error.response.data?.errors?.login?.[0] === t('auth.inactive') || 
         error.response.data?.message === t('auth.inactive'));

      if (isInactive) {
        localStorage.setItem('pending_email', data.email);
        setUser({ email: data.email, is_unverified: true });
      }

      return { success: false, errors: validationErrors };
    } finally {
      setLoading(false);
    }
  }, [navigate, handleError, t]);

  const register = useCallback(async (data: RegisterFormData) => {
    setLoading(true);
    setErrorMessage(null);
    try {
      const result = await authService.register(data);
      localStorage.setItem('auth_token', result.token);
      localStorage.setItem('user', JSON.stringify(result.user));
      setUser(result.user);
      navigate('/verify-email');
      return { success: true };
    } catch (error: any) {
      const validationErrors = handleError(error, 'auth.error_generic');
      return { success: false, errors: validationErrors };
    } finally {
      setLoading(false);
    }
  }, [navigate, handleError]);

  const forgotPassword = useCallback(async (data: ForgotPasswordFormData) => {
    setLoading(true);
    setErrorMessage(null);
    setSuccessMessage(null);
    try {
      await authService.forgotPassword(data);
      setSuccessMessage(t('auth.forgot_password_success', 'Si cette adresse existe, vous recevrez un lien de réinitialisation.'));
      return true;
    } catch (error: any) {
      handleError(error, 'auth.error_generic');
      return false;
    } finally {
      setLoading(false);
    }
  }, [t, handleError]);

  const resetPassword = useCallback(async (data: ResetPasswordFormData) => {
    setLoading(true);
    setErrorMessage(null);
    setSuccessMessage(null);
    try {
      await authService.resetPassword(data);
      setSuccessMessage(t('auth.reset_password_success', 'Votre mot de passe a été réinitialisé avec succès.'));
      return true;
    } catch (error: any) {
      handleError(error, 'auth.error_generic');
      return false;
    } finally {
      setLoading(false);
    }
  }, [t, handleError]);

  const verifyOtp = useCallback(async (code: string, email?: string) => {
    setLoading(true);
    setErrorMessage(null);
    setSuccessMessage(null);
    try {
      await authService.verifyOtp(code, email);
      setSuccessMessage(t('auth.verify_success', 'E-mail vérifié avec succès !'));
      return true;
    } catch (error: any) {
      handleError(error, 'auth.error_generic');
      return false;
    } finally {
      setLoading(false);
    }
  }, [t, handleError]);

  const resendVerification = useCallback(async (email?: string) => {
    setLoading(true);
    setErrorMessage(null);
    setSuccessMessage(null);
    try {
      await authService.resendVerificationEmail(email);
      setSuccessMessage(t('auth.resend_code_success'));
      return true;
    } catch (error: any) {
      handleError(error, 'auth.error_generic');
      return false;
    } finally {
      setLoading(false);
    }
  }, [t, handleError]);

  return {
    loading,
    errorMessage,
    successMessage,
    user,
    login,
    register,
    forgotPassword,
    resetPassword,
    verifyOtp,
    resendVerification,
    setErrorMessage,
    setSuccessMessage
  };
};
