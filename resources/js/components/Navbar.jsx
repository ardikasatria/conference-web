import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [isSticky, setIsSticky] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 50) {
        setIsSticky(true);
      } else {
        setIsSticky(false);
      }
    };

    window.addEventListener('scroll', handleScroll);

    return () => {
      window.removeEventListener('scroll', handleScroll);
    };
  }, []);

  return (
    <nav className={`navbar md:sticky ${isSticky ? 'navbar-fixed' : 'absolute'}`}>
      <div className="container mx-auto flex items-center justify-between p-4">
        <div className="flex items-center space-x-4">
          <Link href="/">
            <h1 className="text-2xl font-bold text-white font-spaceGrotesk">ICSSF</h1>
          </Link>
        </div>
        <div className="hidden md:flex space-x-4 font-bold font-spaceGrotesk">
          <Link href="/" className={`nav-link text-white`}>Home</Link>
          <Link href="/program" className={`nav-link text-white`}>Program</Link>
          <Link href="/author" className={`nav-link text-white`}>Author</Link>
          <Link href="/committee" className={`nav-link text-white`}>Committee</Link>
          <Link href="/information" className={`nav-link text-white`}>Information</Link>
          <Link href="/schedule" className={`nav-link text-white`}>Schedule</Link>
        </div>
        <div className="hidden md:flex space-x-4 font-spaceGrotesk font-bold">
          <a href="/login" className={`mt-2 nav-link text-colorGreen`}>Login</a>
          <a href="/register" className={`nav-link bg-colorGreen text-black px-4 py-2 rounded-full`}>Register</a>
        </div>
        <div className="md:hidden">
          <button onClick={() => setIsOpen(!isOpen)} className={`nav-link text-white`}>
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>
      </div>
      {isOpen && (
        <div className={`md:hidden p-2 font-spaceGrotesk font-medium bg-green-900 font-bold text-white`}>
          <Link href="/" className="block nav-link py-2 mx-4">Home</Link>
          <Link href="/program" className="block nav-link py-2 mx-4">Program</Link>
          <Link href="/author" className="block nav-link py-2 mx-4">Author</Link>
          <Link href="/committee" className="block nav-link py-2 mx-4">Committee</Link>
          <Link href="/information" className="block nav-link py-2 mx-4">Information</Link>
          <Link href="/schedule" className="block nav-link py-2 mx-4">Schedule</Link>
          <a href="/login" className="block nav-link bg-colorGreen mx-4 px-4 py-2 text-black rounded-full my-3">Login</a>
          <a href="/register" className="block nav-link bg-colorGreen mx-4 px-4 py-2 text-black rounded-full my-3">Register</a>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
