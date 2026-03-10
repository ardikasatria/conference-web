import React from 'react';

const Footer = () => {
  return (
    <footer className="bg-black border-t border-gray-800 mt-20">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <div>
            <h3 className="text-white font-bold text-lg mb-4">ICSSF 2024</h3>
            <p className="text-gray-400 text-sm">International Conference on Sustainability of Sciences for the Future</p>
          </div>
          <div>
            <h4 className="text-white font-bold mb-4">Quick Links</h4>
            <ul className="space-y-2 text-gray-400 text-sm">
              <li><a href="#" className="hover:text-white transition">Home</a></li>
              <li><a href="#" className="hover:text-white transition">Program</a></li>
              <li><a href="#" className="hover:text-white transition">Author</a></li>
              <li><a href="#" className="hover:text-white transition">Committee</a></li>
            </ul>
          </div>
          <div>
            <h4 className="text-white font-bold mb-4">Information</h4>
            <ul className="space-y-2 text-gray-400 text-sm">
              <li><a href="#" className="hover:text-white transition">FAQ</a></li>
              <li><a href="#" className="hover:text-white transition">Schedule</a></li>
              <li><a href="#" className="hover:text-white transition">Venue</a></li>
              <li><a href="#" className="hover:text-white transition">Contact</a></li>
            </ul>
          </div>
          <div>
            <h4 className="text-white font-bold mb-4">Contact</h4>
            <p className="text-gray-400 text-sm mb-2">Email: icssf@itera.ac.id</p>
            <p className="text-gray-400 text-sm">Phone: +62838 7772 7466</p>
          </div>
        </div>
        <div className="border-t border-gray-800 pt-8">
          <p className="text-gray-400 text-sm text-center">&copy; 2024 ICSSF. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
