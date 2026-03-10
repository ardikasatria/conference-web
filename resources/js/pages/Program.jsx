import React from 'react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import BannerCustom from '../components/BannerCustom';

const Program = () => {
  return (
    <>
      <Navbar />
      <BannerCustom name="Program" />
      
      <div className="container mx-auto px-4 py-20">
        <div className="text-center mb-16">
          <h1 className="text-4xl md:text-5xl font-bold text-white font-spaceGrotesk mb-4">
            Conference Program
          </h1>
          <hr className="w-24 mx-auto border-2 border-colorGreen" />
        </div>

        <div className="max-w-4xl mx-auto text-white">
          <div className="mb-12">
            <h2 className="text-3xl font-bold text-colorGreen mb-4 font-spaceGrotesk">
              Call for Papers
            </h2>
            <p className="text-lg leading-relaxed mb-4">
              We invite researchers, academics, and industry professionals to submit their original research papers
              for presentation at the conference. Papers should focus on recent advances and innovations in science
              and technology.
            </p>
          </div>

          <div className="mb-12">
            <h2 className="text-3xl font-bold text-colorGreen mb-4 font-spaceGrotesk">
              Conference Sessions
            </h2>
            <div className="space-y-6">
              <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                <h3 className="text-xl font-bold mb-2">Keynote Speakers</h3>
                <p>Distinguished speakers from around the world sharing insights on the latest developments in their fields.</p>
              </div>
              
              <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                <h3 className="text-xl font-bold mb-2">Oral Presentations</h3>
                <p>Selected papers will be presented in oral sessions organized by research tracks.</p>
              </div>
              
              <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                <h3 className="text-xl font-bold mb-2">Poster Sessions</h3>
                <p>Interactive poster presentations allowing in-depth discussions with researchers.</p>
              </div>
              
              <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                <h3 className="text-xl font-bold mb-2">Workshop & Tutorial</h3>
                <p>Hands-on workshops and tutorials on cutting-edge tools and methodologies.</p>
              </div>
            </div>
          </div>

          <div className="mb-12">
            <h2 className="text-3xl font-bold text-colorGreen mb-4 font-spaceGrotesk">
              Important Dates
            </h2>
            <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
              <ul className="space-y-3">
                <li className="flex justify-between border-b border-gray-600 pb-2">
                  <span className="font-semibold">Paper Submission Deadline:</span>
                  <span>TBA</span>
                </li>
                <li className="flex justify-between border-b border-gray-600 pb-2">
                  <span className="font-semibold">Notification of Acceptance:</span>
                  <span>TBA</span>
                </li>
                <li className="flex justify-between border-b border-gray-600 pb-2">
                  <span className="font-semibold">Camera-Ready Submission:</span>
                  <span>TBA</span>
                </li>
                <li className="flex justify-between border-b border-gray-600 pb-2">
                  <span className="font-semibold">Early Registration Deadline:</span>
                  <span>TBA</span>
                </li>
                <li className="flex justify-between">
                  <span className="font-semibold">Conference Dates:</span>
                  <span>TBA</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <Footer />
    </>
  );
};

export default Program;
