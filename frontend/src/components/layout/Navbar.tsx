import { Link } from 'react-router-dom';

export default function Navbar() {
  return (
    <nav className="fixed top-0 w-full z-50 bg-[#fcf9f8]/85 dark:bg-slate-950/85 backdrop-blur-2xl shadow-[0_8px_24px_rgba(0,0,0,0.06)]">
      <div className="flex justify-between items-center px-6 py-4 max-w-7xl mx-auto">
        <Link to="/" className="text-2xl font-black text-[#a20513] dark:text-[#c62828] tracking-tighter font-headline">
          Wasitni
        </Link>
        <div className="hidden md:flex items-center space-x-8">
          <Link to="/explore" className="text-slate-600 font-medium hover:text-[#a20513] transition-colors font-headline">
            Explorer
          </Link>
          <Link to="/trips" className="text-slate-600 font-medium hover:text-[#a20513] transition-colors font-headline">
            Trajets
          </Link>
          <Link to="/register" className="bg-primary text-on-primary px-6 py-2 rounded-full font-bold tracking-tight hover:scale-105 transition-transform active:scale-95">
            Sign Up
          </Link>
        </div>
      </div>
    </nav>
  );
}
