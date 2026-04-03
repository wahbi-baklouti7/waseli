import { Link } from 'react-router-dom';

export default function Footer() {
  return (
    <footer className="w-full border-t border-[#e4beba]/15 bg-[#fcf9f8] dark:bg-slate-950 mt-auto">
      <div className="flex flex-col md:flex-row justify-between items-center px-8 py-12 space-y-4 md:space-y-0 max-w-7xl mx-auto">
        <div className="flex flex-col items-center md:items-start space-y-2">
          <div className="text-lg font-bold text-[#a20513] font-headline">Wasitni</div>
          <p className="text-slate-500 text-sm font-body">© {new Date().getFullYear()} Wasitni. The Digital Concierge.</p>
        </div>
        <div className="flex flex-wrap justify-center gap-x-8 gap-y-2">
          <Link to="/terms" className="text-slate-500 text-sm font-body hover:text-[#a20513] underline decoration-2 underline-offset-4 transition-colors">
            Terms of Service
          </Link>
          <Link to="/privacy" className="text-slate-500 text-sm font-body hover:text-[#a20513] underline decoration-2 underline-offset-4 transition-colors">
            Privacy Policy
          </Link>
          <Link to="/help" className="text-slate-500 text-sm font-body hover:text-[#a20513] underline decoration-2 underline-offset-4 transition-colors">
            Help Center
          </Link>
          <Link to="/contact" className="text-slate-500 text-sm font-body hover:text-[#a20513] underline decoration-2 underline-offset-4 transition-colors">
            Contact
          </Link>
        </div>
      </div>
    </footer>
  );
}
