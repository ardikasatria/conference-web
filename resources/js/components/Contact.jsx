import React from 'react';
import { Github, Youtube, Instagram } from 'lucide-react';
import { Link } from '@inertiajs/react';

const Contact = () => {
  return (
    <div className="flex flex-col items-center justify-center mx-10 mt-32 gap-5">
      <div className="flex flex-col md:flex-row text-white items-center justify-between w-full">
        <h1 className="text-5xl font-spaceGrotesk font-bold text-center md:text-left">Connect With Us</h1>
        <p className="font-plusJakarta text-center md:text-right w-full md:w-1/2 mt-3 md:mt-0">We are excited to connect with you! If you have any questions or need further information about the conference, feel free to reach out to us.</p>
      </div>
      <div className="flex flex-col md:flex-row gap-3 w-full">
        <a href="mailto:icssf@itera.ac.id" className='bg-black py-3 px-5 rounded text-md text-center text-white w-full hover:bg-gray-800 transition'>
          icssf@itera.ac.id
        </a>
        <div className='bg-black py-3 px-5 rounded text-md text-center text-white w-full'>+62838 7772 7466 (Adjie)</div>
        <div className='bg-black py-3 px-5 rounded text-md text-center text-white w-full'>+62857 6836 7414 (Nisa)</div>
        <div className="flex gap-3 justify-center w-full md:w-auto">
          <a href="#" className="bg-black p-3 rounded text-gray-400 hover:text-white hover:bg-gray-800 flex justify-center items-center w-full md:w-auto transition">
            <Github size={20}/>
          </a>
          <a href="#" className="bg-black p-3 rounded text-gray-400 hover:text-white hover:bg-gray-800 flex justify-center items-center w-full md:w-auto transition">
            <Youtube size={20}/>
          </a>
          <a href="#" className="bg-black p-3 rounded text-gray-400 hover:text-white hover:bg-gray-800 flex justify-center items-center w-full md:w-auto transition">
            <Instagram size={20}/>
          </a>
        </div>
      </div>
    </div>
  );
};

export default Contact;
