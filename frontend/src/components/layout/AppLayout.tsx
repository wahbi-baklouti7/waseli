import { Outlet, useLocation } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';

export default function AppLayout() {
  const location = useLocation();
  const isAuthPage = ['/login', '/register', '/forgot-password', '/verify-email', '/reset-password', '/500'].includes(location.pathname);

  return (
    <div className="flex flex-col min-h-screen">
      {!isAuthPage && <Navbar />}
      <div className={`flex-grow flex flex-col ${!isAuthPage ? 'pt-20' : ''}`}>
        <Outlet />
      </div>
      {!isAuthPage && <Footer />}
    </div>
  );
}
